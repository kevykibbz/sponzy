<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;

class Flutterwave
{
    const BASEURL = 'https://api.flutterwave.com/v3';

    /**
     * Generates a unique reference
     * @param $transactionPrefix
     * @return string
     */

     public static function generateReference(String $transactionPrefix = NULL)
     {
         if ($transactionPrefix) {
             return $transactionPrefix . '_' . uniqid(time());
         }
         return 'flw_' . uniqid(time());
     }

     /**
     * Reaches out to Flutterwave to initialize a payment
     * @param $data
     * @return object
     */
    public static function initializePayment(array $data)
    {
        $secretKey = config('flutterwave.secretKey');

        $payment = Http::withToken($secretKey)->post(
            self::BASEURL . '/payments',
            $data
        )->json();

        return $payment;
    }

    /**
     * Gets a transaction ID depending on the redirect structure
     * @return string
     */
    public static function getTransactionIDFromCallback()
    {
        $transactionID = request()->transaction_id;

        if (!$transactionID) {
            $transactionID = json_decode(request()->resp)->data->id;
        }

        return $transactionID;
    }

    /**
     * Reaches out to Flutterwave to verify a transaction
     * @param $id
     * @return object
     */
    public static function verifyTransaction($id)
    {
        $secretKey = config('flutterwave.secretKey');

        $data =  Http::withToken($secretKey)->get(self::BASEURL . "/transactions/" . $id . '/verify')->json();
        return $data;
    }
}
