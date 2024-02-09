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

        if ( $reservation->package->price <= 0 )
            return $this->reservationPaid($reservation);

        $callback = route('reservation.callBack' , $reservation);
        $payment = new payment();
        $result = $payment->process_payment($reservation->id ,$callback, $reservation->package->price , $reservation->phone , '' ,$reservation->name);
        if ( $result['result'] )
            return redirect()->to($result['redirect']);
        return ;
    }
    public function callBack(Reservation $reservation = null){

        if (
            $reservation->is_paid
        )
            abort(404);
        $payment = new payment();
        list($status , $invoiceId , $invoiceReference) = $payment->callback($reservation->id);
        if ( $status ){
            return $this->reservationPaid($reservation , $invoiceId , $invoiceReference );
        }
        return redirect()->route('reservation.detail' , $reservation);

    }

    public function reservationPaid(Reservation $reservation , $invoiceId = null , $invoiceReference = null){
        if ( $invoiceId )
            $reservation->invoice_id = $invoiceId;

        if ( $invoiceId )
            $reservation->reference_number = $invoiceReference;

        $reservation->is_paid = true;
        $reservation->paid_at = now();
        $reservation->save();
//        dispatch(new sendRegisterEmailJob($application->id));
        if ( $reservation->phone )
            DezSMS::send($reservation->phone  , 'لقد تم حجز طلبك لعام '.$reservation->from .'. رمز الحجز هو :'. $reservation->uuid);
        if ( Settings::get('telephoneNotification' , false) )
            DezSMS::send(Settings::get('telephoneNotification') , 'new request has been reserved for: '.$reservation->from);
        return redirect()->route('reservation.detail' , $reservation );
    }

}
