<?php

namespace App\Library\Mpesa;

use App\Library\Mpesa\Service;

class STK extends Service
{
    /**
     * @param Integer $phone The MSISDN sending the funds.
     * @param Integer $amount The amount to be transacted.
     * @param String $reference Used with M-Pesa PayBills.
     * @param String $description A description of the transaction.
     * @param String $remark Remarks
     *
     * @return array Response
     */
    public static function send(
        $phone,
        $amount,
        $reference = "ACCOUNT",
        $description = "Transaction Description",
        $remark = "Remark",
        $callback = null
    ) {

        $phone = (substr(strval($phone), 0, 1) == "+") ? str_replace("+", "", strval($phone)) : $phone;
        $phone = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "254", $phone) : $phone;
        $phone = (substr($phone, 0, 1) == "7") ? "254{$phone}" : $phone;

        $timestamp = date("YmdHis");
        $password  = base64_encode(parent::$config->shortcode . parent::$config->passkey . $timestamp);

        $endpoint = (parent::$config->env == "live")
            ? "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest"
            : "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
            
            
            
        $curl_post_data = array(
            "BusinessShortCode" => parent::$config->shortcode,
            "Password"          => $password,
            "Timestamp"         => $timestamp,
            "TransactionType"   => parent::$config->type,
            "Amount"            => round($amount),
            "PartyA"            => $phone,
            "PartyB"            => (parent::$config->type == 'CustomerPayBillOnline') ? parent::$config->shortcode : parent::$config->headoffice,
            "PhoneNumber"       => $phone,
            "CallBackURL"       => parent::$config->callback_url,
            "AccountReference"  => $reference,
            "TransactionDesc"   => $description,
            "Remark"            => $remark,
        );

        $response = parent::remote_post($endpoint, $curl_post_data);
        $result   = json_decode($response, true);

        /*

            $results=array:5 [ 
                "MerchantRequestID" => "96678-179378708-1"
                "CheckoutRequestID" => "ws_CO_06122023171149009796268817"
                "ResponseCode" => "0"
                "ResponseDescription" => "Success. Request accepted for processing"
                "CustomerMessage" => "Success. Request accepted for processing"
            ]

        */

        return is_null($callback)
            ? $result
            : \call_user_func_array($callback, array($result));
    }
}
