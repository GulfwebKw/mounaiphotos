<?php

namespace App\Http\Controllers;

use App\Helpers\DezSMS;
use App\Helpers\payleq8_com\payment;
use App\Jobs\sendRegisterEmailJob;
use App\Models\Reservation;
use HackerESQ\Settings\Facades\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class PaymentController
{
    public function pay(Reservation $reservation){
        if (
            $reservation->is_paid or
            $reservation->created_at->lt( now()->subMinutes(Controller::$reserveTime) ) or
            $reservation->package == null or
            $reservation->package->deleted_at != null
        )
            abort(404);

        $price = $reservation->number_of_persons * $reservation->package->price;
        if ( $price <= 0 )
            return $this->reservationPaid($reservation);

        $callback = route('reservation.callBack' , $reservation);
        $payment = new payment();
        $result = $payment->process_payment($reservation->id ,$callback, $price , $reservation->phone , '' ,$reservation->name , $reservation->uuid);
        if ( $result['result'] ) {
            $reservation->price = $price;
            $reservation->save();
            return redirect()->to($result['redirect']);
        }
        return ;
    }
    public function callBack(Reservation $reservation = null){

        if (
            $reservation->is_paid
        )
            abort(404);
        $payment = new payment();
        list($status , $invoiceId , $invoiceReference , $objectId) = $payment->callback();
        if ( $status and $reservation->id == $objectId){
            return $this->reservationPaid($reservation , $invoiceId , $invoiceReference );
        }
        return redirect()->route('reservation.detail' , $reservation);

    }
    public function callBackTransaction(){
        try {
            $payment = new payment();
            list($status, $invoiceId, $invoiceReference, $objectId) = $payment->callback();
            $reservation = Reservation::query()->find($objectId);
            if ($status and $reservation->id == $objectId and !$reservation->is_paid) {
                return $this->reservationPaid($reservation, $invoiceId, $invoiceReference);
            }
            return redirect()->route('reservation.detail' , $reservation);
        }catch (\Exception $exception){ abort(404) ; }
    }

    public function reservationPaid($reservation , $invoiceId = null , $invoiceReference = null){
        if ( $invoiceId )
            $reservation->invoice_id = $invoiceId;

        if ( $invoiceId )
            $reservation->reference_number = $invoiceReference;

        $reservation->is_paid = true;
        $reservation->paid_at = now();
        $reservation->save();
//        dispatch(new sendRegisterEmailJob($application->id));
        if ( $reservation->phone )
            DezSMS::send($reservation->phone  , 'Your booking has been confirmed for mounai studio.' .chr(10). 'سوف يتم التواصل واتساب لإرسال العنوان واللوكيشن قبل الموعد بيوم.'.chr(10). 'الرجاء الاحتفاظ بـكابجر الدفع ورقم الحجز '.$reservation->uuid .chr(10).'التاريخ: '.$reservation->from );
        if ( Settings::get('telephoneNotification' , false) )
            DezSMS::send(Settings::get('telephoneNotification') , 'new request has been reserved for: '.$reservation->from);
        return redirect()->route('reservation.detail' , $reservation );
    }

}
