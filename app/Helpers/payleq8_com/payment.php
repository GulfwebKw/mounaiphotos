<?php

namespace App\Helpers\payleq8_com;

use HackerESQ\Settings\Facades\Settings;
use Illuminate\Support\Facades\Log;

class payment
{
    const APIURL	= 'https://trans.payleq8.com/gatewaypublicinit';
    const TESTURL	= 'https://sandpay.payleq8.com/payinit';
    const FEEURL	= 'https://trans.payleq8.com/api/requestfullamountspublicusergateway';
    const FEETEST	= 'https://sandpay.payleq8.com/apifee/requestfullamounts';

    /**
     * Class constructor
     * Contains the basic initializing functionality
     */
    function __construct()
    {
        $this->title			= 'Pay with PayLe';
        $this->description		= 'Make your payment for the order with PayLe.';
        $this->mid				= Settings::get('PayLeMerchantAccountID');
        $this->mpass			= Settings::get('PayLeAccountPassword');
        $this->mkey				= Settings::get('PayLeResourceKey');
        $this->bizcode			= Settings::get('PayLeBusinessCode');
        $this->sandbox			= Settings::get('PayLe_IS_LIVE' , false);
        $this->language			= 'ar'; // ar en
        $this->paylemethod      = 1; // 1 : kent , 3: Visa / Mastercard"
    }

    /**
     * Main function which processes the payment
     */
    function process_payment($order_id , $callback , $total , $phone , $email , $name , $uuid = null )
    {


        $callback = route('callBack');

        $data		= compact('order_id', 'callback', 'total', 'phone', 'email', 'name' , 'uuid');

        $apiurl		= self::APIURL;
        if (! $this->sandbox) {
            $apiurl		= self::TESTURL;
        }

        $lang		= $this->language;
        $transdata	= $this->transaction_data($data);
        $mid		= $this->mid;
        $version	= '0';
        $redirect	= $apiurl . '/' . $lang . '/' . $transdata . '/' . $mid . '/' . $version;

        Log::info('redirect to: ', [$redirect]);
        return array(
            'result'   => true,
            'redirect' => $redirect,
        );
    }

    /**
     * The callback function that PayLe
     * uses to send updates about the payment
     */
    function callback()
    {
        if (request()->get('refnumber' , false)) {
            // Check if transaction attempt was successful
            if (request()->get('code') == "00") {
                $transdata	= request()->get('transDate');
                $phpclass	= new PHP_AES_Cipher;
                $transstr	= $phpclass->decrypt($transdata, $this->mkey);
                parse_str($transstr, $trans);
                // Check if transaction was successful
                if (isset($trans['result']) && intval($trans['result']) == 1 ) {
                    return [true ,  request()->get('trackNumber') , $trans['paymentid'] , $trans['invoicekey']];
                }
            }
        }
        return [false , request()->get('trackNumber' , null) , null , request()->get('refnumber' , null) ];
    }

    function get_fullAmount($amount)
    {

        if (! $this->sandbox) {
            $feeurl		= self::FEETEST;
        } else {
            $feeurl		= self::FEEURL;
        }

        // Prepare cURL request
        $curl_config    = [
            CURLOPT_URL => $feeurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36",
                "Accept-Language:en-US,en;q=0.5"
            ),
            CURLOPT_SSL_VERIFYPEER => false
        ];

        // Prepare post fields
        $payload	= [
            'lang'			=> $this->language,
            'initialAmount'	=> $amount,
            'businessCode'	=> $this->bizcode,
        ];
        $curl_config[CURLOPT_POSTFIELDS]    = $payload;

        // Send the request
        $curl       = curl_init();
        curl_setopt_array($curl, $curl_config);
        $response   = curl_exec($curl);
        curl_close($curl);

        $paytype	= $this->paylemethod;

        // Parse the response to get fullAmount
        $result		= json_decode($response);
        Log::info('get full amount: ', (array) $result);
        $records	= $result->records;
        foreach ($records as $i => $r) {
            if ($r->id == $paytype) {
                return $r->fullAmount;
            }
        }
        $firstrec	= $records[0];
        return $firstrec->fullAmount;
    }

    /**
     * The function creates the encrypted string of transaction data
     * to pass into payment gateway redirection URL
     */
    function transaction_data($data)
    {
        $TranportalId		= $this->mid;
        $ReqTranportalId	= "tpid=" . $TranportalId . "&";

        $TranportalKey		= $this->mpass;
        $ReqTranportalKey	= "tpkey=" . $TranportalKey . "&";

        $TranAmount			= $data['total'];
        $ReqAmount			= "amount=" . $TranAmount . "&";

        $TranFullAmount		= $this->get_fullAmount($TranAmount);
        $ReqFullAmount		= "fullamount=" . $TranFullAmount . "&";

        $paymenttype		= $this->paylemethod;
        $ReqPaymentType		= "paymenttype=" . $paymenttype . "&";

        $businesscode		= $this->bizcode;
        $ReqBusinesscode	= "businesscode=" . $businesscode . "&";

        $invoicekey			= $data["order_id"];
        $ReqInvoiceKey		= "invoicekey=" . $invoicekey . "&";

        $udf1 = $data["callback"];
        $udf2 = '';
        $udf3 = '';

        $ReqUdf1 = "udf1=" . $udf1 . "&";
        $ReqUdf2 = "udf2=" . $udf2 . "&";
        $ReqUdf3 = "udf3=" . $udf3 . "&";

        $paymentmedia		= 1;
        $ReqPaymentMedia	= "paymentmedia=" . $paymentmedia . "&";
        $buyerMobile		= $data['phone'];
        $buyerEmail			= $data['email'];
        $buyername			= $data['name'];

        $ReqPaymentBuyerMobile	= "buyermobile=" . $buyerMobile . "&";
        $ReqPaymentBuyerEmail	= "buyeremail=" . $buyerEmail . "&";
        $ReqPaymentBuyerName	= "buyername=" . $buyername . "&";

        $transactionRequestData = $ReqPaymentMedia . $ReqAmount . $ReqFullAmount . $ReqPaymentType . $ReqBusinesscode . $ReqInvoiceKey . $ReqUdf1 . $ReqUdf2 . $ReqUdf3 . $ReqTranportalId . $ReqTranportalKey . $ReqPaymentBuyerMobile . $ReqPaymentBuyerEmail . $ReqPaymentBuyerName;

        $phpclass = new PHP_AES_Cipher;
        $encriptData = $phpclass->encrypt($transactionRequestData, $this->mkey);

        Log::info('new payment request: ', [$transactionRequestData , $this->mkey , $encriptData]);
        return $encriptData;
    }
}
