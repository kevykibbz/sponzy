<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Subscriptions;
use App\Models\Notifications;
use App\Models\Messages;
use App\Models\User;
use App\Models\Updates;
use App\Models\Deposits;
use App\Models\Plans;
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;
use App\Models\Transactions;
use App\Library\Mpesa\STK;
use App\Models\MpesaTransaction;

class MpesaController extends Controller
{
  use Traits\Functions;

  public function __construct(AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

  /**
   * Show/Send form Mpesa
   *
   * @return response
   */
    public function show()
    {
    
        // if (! $this->request->expectsJson()) {
        //     abort(404);
        // }
    
        $user = User::find($this->request->id);
        
        $plan = $user->plans()
              ->whereInterval($this->request->interval)
                 ->firstOrFail();
        
            
              $subscription = new Subscriptions();
              $subscription->user_id = auth()->id();
              $subscription->stripe_price = $plan->name;
              $subscription->subscription_id = $this->request->mpesaReceiptNumber;
              $subscription->ends_at = $user->planInterval($plan->interval);
              $subscription->interval = $plan->interval;
              $subscription->save();

              // Send Notification to User --- destination, author, type, target
              Notifications::send($this->request->id, auth()->id(), '1', $this->request->id);
        
            $payment = PaymentGateways::whereName('Mpesa')->first();
              
              // Admin and user earnings calculation
              $earnings = $this->earningsAdminUser($user->custom_fee, $plan->price, $payment->fee, $payment->fee_cents);

              $txnId = $this->request->mpesaReceiptNumber;

              $verifiedTxnId = Transactions::where('txn_id', $txnId)->first();

              if (! isset($verifiedTxnId)) {
                // Insert Transaction
                $this->transaction(
                    $txnId,
                    auth()->id(),
                    $subscription->id,
                    $this->request->id,
                    $plan->price,
                    $earnings['user'],
                    $earnings['admin'],
                    'Mpesa',
                    'subscription',
                    $earnings['percentageApplied'],
                    null
                  );

                // Add Earnings to User
                $user->increment('balance', $earnings['user']);

                }// End verifiedTxnId
                
                return redirect(session('redirectUrl'));
                
            //     return response()->json([
            //     'success' => true,
            //     'url' => session('redirectUrl'),
            // ]);

    }
  
  public function requestMerchantId(Request $request){
      
      session()->put('redirectUrl', url()->previous());
      $mpesaConfig = PaymentGateways::whereName('Mpesa')->whereEnabled(1)->firstOrFail();
        // Init Mpesa
        STK::init(
                array(
                    'env'              => ($mpesaConfig->sandbox === 'true') ? 'sandbox' : 'live',
                    'type'             => $mpesaConfig->shortcode_type,
                    'shortcode'        => $mpesaConfig->shortcode,
                    'headoffice'       => $mpesaConfig->storenumber,
                    'key'              => $mpesaConfig->key,
                    'secret'           => $mpesaConfig->key_secret,
                    'passkey'          => $mpesaConfig->passkey,
                    'callback_url'     => route('mpesaCallback'),
                )
        );
        
        if($this->settings->currency_code != 'KES'){            
                $amount = STK::convertCurrency($this->settings->currency_code, 'KES', $request->amount, $mpesaConfig->converter_api);
            }else{
                $amount = $request->amount;
        }
     

        $ref = STK::generateReference();
        
        $response = STK::send($request->phone, round($amount), $ref);

        if (!$response) {
            return response()->json([
                'result' => false,
                'message' => 'Payment Failed'
            ]);
        }

        if (array_key_exists('errorMessage', $response)) {
            return response()->json([
                'result' => false,
                'message' => $response['errorMessage']
            ]);
        }

        if ($response['ResponseCode'] != 0) {
            return response()->json([
                'result' => false,
                'message' => 'Payment Incomplete'
            ]);
        }

        $this->createTransaction($amount, $response['MerchantRequestID']); 

        return response()->json([
            'result' => true,
            'MerchantRequestID' => $response['MerchantRequestID']
        ]);


    }
    
    public function checkPayment(Request $request){
        $transaction = MpesaTransaction::where('merchantRequestID', $request->MerchantRequestID)->first();

        if($transaction && $transaction->mpesaReceiptNumber !=null){
            return response()->json([
                'result' => true,
                'message' => 'Payment Successful',
                'mpesaReceiptNumber' => $transaction->mpesaReceiptNumber
            ]);
        }else{
            return response()->json([
                'result' => false,
                'message' => 'Payment Incomplete'
            ]);
        }
    }
    
    private function createTransaction($amount, $MerchantRequestID)
    {   

            $mpesaTransaction = new MpesaTransaction();
            $mpesaTransaction->user_id = auth()->id();
            $mpesaTransaction->merchantRequestID = $MerchantRequestID;
            $mpesaTransaction->amount = $amount; 
            $mpesaTransaction->plan_name = 'true';
            $mpesaTransaction->save();

        return $mpesaTransaction;
    }
}