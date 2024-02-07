<?php

namespace App\Http\Controllers;

use App\Jobs\sendRegisterEmailJob;
use App\Models\Application;
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

//        $callback = route('callBack' , $reservation->uuid);

        return ;
    }
    public function callBack(Request $request, Application $application = null){

        $method = self::findMethod($application->gateway) . 'Callback';
        list($status , $msg ,$invoiceId , $invoiceReference , $application) =  self::{$method}($request , $application);
        if ( $application == null)
            abort(404);

        if ( $status ){
            return self::applicationPaid($application , $invoiceId , $invoiceReference );
        }
        return redirect()->route('reservation.detail' , ['uuid' => $application->uuid , 'msg' => $msg ]);

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
        return redirect()->route('reservation.detail' , $reservation );
    }

}
