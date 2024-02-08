<?php

namespace App\Helpers;

use App\Models\Setting;
use Curl;
use HackerESQ\Settings\Facades\Settings;
use Illuminate\Support\Facades\Log;

class DezSMS
{
    private static $dez_sms_api_url = 'https://www.dezsms.com/json_dezsmsnewapi.php';

    public function __construct()
    {
        //
    }

    //send sms to dezsms
    public static function send($to, $sms_msg)
    {
        try {
            $jsdecode = '';

            if (
                ! empty($to) &&
                ! empty($sms_msg) &&
                ! empty(Settings::get('DezSMS_user_id')) &&
                ! empty(Settings::get('DezSMS_sender_name')) &&
                ! empty(Settings::get('DezSMS_api_key'))
            ) {
                // $to = ! str_starts_with($to, '965') ? '965'.$to : $to;
                $response = self::sendMsgRequest([
                    'key' => Settings::get('DezSMS_api_key'),
                    'dezsmsid' => Settings::get('DezSMS_user_id'),
                    'senderid' => Settings::get('DezSMS_sender_name'),
                    'msg' => $sms_msg,
                    'number' => $to,
                ]);
                $jsdecode = json_decode($response, true);
                $status = $jsdecode[0]['status'];
                $message = self::getErrorMsg($status);
                $jsdecode = ['status' => $status, 'message' => $message];
            } else {
                $jsdecode = ['status' => '404', 'message' => 'Credentials are missing'];
            }

            return $jsdecode;
        } catch (\Exception $e) {
            Log::error('DEZ SMS: unable to send sms', $e->getTrace());

            return false;
        }
    }

    //get dezsms total points via api
    public static function getPoints()
    {
        if (! empty(Settings::get('DezSMS_user_id')) && ! empty(Settings::get('DezSMS_sender_name')) && ! empty(Settings::get('DezSMS_api_key'))) {
            $apiUrl = 'https://www.dezsms.com/json_dezsmsnewapi_totalpoints.php';
            $response = Curl::to($apiUrl)
                ->withData([
                    'key' => Settings::get('DezSMS_api_key'),
                    'usermobile' => Settings::get('DezSMS_user_id'),
                ])->post();
            $jsdecode = json_decode($response, true);
        } else {
            $jsdecode = ['status' => 'error', 'message' => 'Credentials are missing'];
        }

        return $jsdecode;
    }

    //get Dezsms error text message via code
    public static function getErrorMsg($Error)
    {
        if ($Error == 100) {
            $txt = 'SMS has been sent successfully';
        } elseif ($Error == 101) {
            $txt = 'This is Invalid user';
        } elseif ($Error == 102) {
            $txt = 'Invalid authentication key!';
        } elseif ($Error == 103) {
            $txt = 'Mobile number OR Message is required!';
        } elseif ($Error == 104) {
            $txt = 'You can send upto 200 maximum mobile numbers at once.';
        } elseif ($Error == 105) {
            $txt = 'SMS Sending failed.Please contact with your SMS provider.';
        } elseif ($Error == 106) {
            $txt = 'Arabic text should not be greater than 258';
        } elseif ($Error == 107) {
            $txt = 'English text should not be greater than 526';
        } elseif ($Error == 108) {
            $txt = 'Your account is not activeted';
        } elseif ($Error == 109) {
            $txt = 'Your account has been expired.';
        } elseif ($Error == 110) {
            $txt = 'SMS point is not enough to send sms';
        } elseif ($Error == 111) {
            $txt = 'Invalid Mobile number';
        }

        return $txt;
    }

    private static function sendMsgRequest(array $POSTFIELDS)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => self::$dez_sms_api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $POSTFIELDS,
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
