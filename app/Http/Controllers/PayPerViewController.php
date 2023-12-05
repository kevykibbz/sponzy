<?php

namespace App\Http\Controllers;

use Mail;
use App\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminSettings;
use Fahim\PaypalIPN\PaypalIPNListener;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentGateways;
use App\Models\Transactions;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Notifications;
use App\Models\Conversations;
use App\Models\Messages;
use App\Models\Updates;
use App\Models\PayPerViews;
use Yabacon\Paystack;
use App\Library\Mpesa\STK;
use App\Models\MpesaTransaction;

class PayPerViewController extends Controller
{
  use Traits\Functions;

  public function __construct(Request $request, AdminSettings $settings) {
    $this->request = $request;
    $this->settings = $settings::first();
  }

  /**
	 *  Send Request
	 *
	 * @return Response
	 */
  public function send()
  {
    if ($this->request->isMessage) {
      // Find Message
      $media = Messages::whereId($this->request->id)
            ->wherePrice($this->request->amount)
            ->whereToUserId(auth()->user()->id)
            ->firstOrFail();

      // Verify that the user has not purchased the content
      if (PayPerViews::whereUserId(auth()->user()->id)->whereMessagesId($this->request->id)->first()) {
        return response()->json([
          "success" => false,
          "errors" => ['error' => __('general.already_purchased_content')]
        ]);
      }

    } else {
      // Find Post
      $media = Updates::whereId($this->request->id)
            ->wherePrice($this->request->amount)
            ->where('user_id', '<>', auth()->user()->id)
            ->firstOrFail();

      // Verify that the user has not purchased the content
      if (PayPerViews::whereUserId(auth()->user()->id)->whereUpdatesId($this->request->id)->first()) {
        return response()->json([
          "success" => false,
          "errors" => ['error' => __('general.already_purchased_content')]
        ]);
      }
    }

    $messages = [
      'payment_gateway_ppv.required' => trans('general.choose_payment_gateway')
    ];

  //<---- Validation
  $validator = Validator::make($this->request->all(), [
      'payment_gateway_ppv' => 'required'
      ], $messages);

    if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

    switch ($this->request->payment_gateway_ppv) {
      case 'wallet':
        return $this->sendWallet();
        break;

      case 'PayPal':
        return $this->sendPayPal();
        break;

      case 'Stripe':
        return $this->sendStripe();
        break;

      case 'CCBill':
        return $this->sendCCbill();
        break;

      case 'Paystack':
        return $this->sendPaystack();
        break;
      case 'Mpesa':
        return $this->sendMpesa();
        break;
    }


    return response()->json([
      'success' => true,
      'insertBody' => '<i></i>'
    ]);

  } // End method Send

  /**
	 *  Send  Wallet
	 *
	 * @return Response
	 */
   protected function sendWallet()
   {
     $amount = $this->request->amount;

     if (auth()->user()->wallet < Helper::amountGross($amount)) {
       return response()->json([
         "success" => false,
         "errors" => ['error' => __('general.not_enough_funds')]
       ]);
     }

     // Check if it is a Message or Post
     $media = $this->request->isMessage ? Messages::find($this->request->id) : Updates::find($this->request->id);

     // Admin and user earnings calculation
     $earnings = $this->earningsAdminUser($media->user()->custom_fee, $amount, null, null);

     // Insert Transaction
     $this->transaction(
         'ppv_'.str_random(25),
         auth()->user()->id,
         0,
         $media->user()->id,
         $amount,
         $earnings['user'],
         $earnings['admin'],
         'Wallet',
         'ppv',
         $earnings['percentageApplied'],
         auth()->user()->taxesPayable()
     );

     // Add Earnings to User
     $media->user()->increment('balance', $earnings['user']);

     // Subtract user funds
     auth()->user()->decrement('wallet', Helper::amountGross($amount));

      // Check if is sent by message
      if ($this->request->isMessage) {
        // $user_id, $updates_id, $messages_id
        $this->payPerViews(auth()->user()->id, 0, $this->request->id);
        $url = url('messages', $media->user()->id);

        // Send Email Creator
        if ($media->user()->email_new_ppv == 'yes') {
          $this->notifyEmailNewPPV($media->user(), auth()->user()->username, $media->message, 'message');
        }

        // Send Notification - destination, author, type, target
        Notifications::send($media->user()->id, auth()->user()->id, '6', $this->request->id);

        // Get message to show live
        $message = Messages::whereId($this->request->id)->get();

        $data = view('includes.messages-chat', [
       			'messages' => $message,
       			'allMessages' => 0,
       			'counter' => 0
       			])->render();

        $msgId = $this->request->id;

      } else {
        // $user_id, $updates_id, $messages_id
        $this->payPerViews(auth()->user()->id, $this->request->id, 0);
        $url = url($media->user()->username, 'post').'/'.$this->request->id;

        // Send Email Creator
        if ($media->user()->email_new_ppv == 'yes') {
          $this->notifyEmailNewPPV($media->user(), auth()->user()->username, $media->description, 'post');
        }

        // Send Notification - destination, author, type, target
        Notifications::send($media->user()->id, auth()->user()->id, '7', $this->request->id);
      }

     return response()->json([
       "success" => true,
       "url" => $url,
       "data" => $data ?? false,
       "msgId" => $msgId ?? false,
       "wallet" => Helper::userWallet()
     ]);

   } // End sendWallet


  /**
	 *  Send  PayPal
	 *
	 * @return Response
	 */
  protected function sendPayPal()
  {
    // Get Payment Gateway
    $payment = PaymentGateways::whereId(1)->whereName('PayPal')->firstOrFail();

    // Check if it is a Message or Post
    $media = $this->request->isMessage ? Messages::find($this->request->id) : Updates::find($this->request->id);

      if ($this->request->isMessage) {
        $urlSuccess = route('paypal.success');
        $urlCancel  = url('messages', $media->user()->id);
        $isMessage  = true;
      } else {
        $urlSuccess = route('paypal.success');
        $urlCancel  = url($media->user()->username);
        $isMessage = false;
      }

      try {
        // Init PayPal
        $provider = new PayPalClient();
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $order = $provider->createOrder([
              "intent"=> "CAPTURE",
              'application_context' =>
                  [
                      'return_url' => $urlSuccess,
                      'cancel_url' => $urlCancel,
                      'shipping_preference' => 'NO_SHIPPING'
                  ],
              "purchase_units"=> [
                   [
                      "amount"=> [
                          "currency_code"=> $this->settings->currency_code,
                          "value"=> Helper::amountGross($this->request->amount),
                          'breakdown' => [
                            'item_total' => [
                              "currency_code"=> $this->settings->currency_code,
                              "value"=> Helper::amountGross($this->request->amount)
                            ],
                          ],
                      ],
                       'description' => __('general.unlock_content').' @'.$media->user()->username,

                       'items' => [
                         [
                           'name' => __('general.unlock_content').' @'.$media->user()->username,
                            'category' => 'DIGITAL_GOODS',
                              'quantity' => '1',
                              'unit_amount' => [
                                "currency_code"=> $this->settings->currency_code,
                                "value" => Helper::amountGross($this->request->amount)
                              ],
                         ],
                      ],

                      'custom_id' => http_build_query([
                          'id' => $this->request->id,
                          'amount' => $this->request->amount,
                          'sender' => auth()->id(),
                          'm' => $isMessage,
                          'taxes' => auth()->user()->taxesPayable(),
                          'type' => 'ppv'
                      ]),
                  ],
              ],
          ]);

          return response()->json([
                      'success' => true,
                      'url' => $order['links'][1]['href']
                  ]);

        } catch (\Exception $e) {

          \Log::debug($e);

          return response()->json([
            'errors' => ['error' => $e->getMessage()]
          ]);
        }
      } // sendPayPal

  /**
	 *  Send  Stripe
	 *
	 * @return Response
	 */
  public function sendMpesa()
  {
     
      try {
        // Get Payment Gateway
        $payment = PaymentGateways::whereName('Mpesa')->firstOrFail();

        $amountFixed = $this->request->amount;

        $mpesaConfig = PaymentGateways::whereName('Mpesa')->firstOrFail();

        STK::init(
          array(
              'env'              => ($mpesaConfig->sandbox === 'true') ? 'sandbox' : 'live',
              'type'             => $mpesaConfig->shortcode_type,
              'shortcode'        => $mpesaConfig->shortcode,
              'headoffice'       => $mpesaConfig->storenumber,
              'key'              => $mpesaConfig->key,
              'secret'           => $mpesaConfig->key_secret,
              'passkey'          => $mpesaConfig->passkey,
              'callback_url'     => route('mpesaPPVIpn'),
          )
        );

        $reference = STK::generateReference();

        if($this->settings->currency_code != 'KES'){            
          $amount = STK::convertCurrency($this->settings->currency_code, 'KES', $amountFixed, $mpesaConfig->converter_api);
        }else{
          $amount = $amountFixed;
        }

        $response = STK::send($this->request->mpesa_number, $amount, $reference);

        if (!$response) {
            return response()->json([
                'success' => false,
                'errors' => ['error' => 'Payment Failed'],
            ]);
        }

        if (array_key_exists('errorMessage', $response)) {
            return response()->json([
              'success' => false,
              'errors' => ['error' => $response['errorMessage']],
            ]);
        }

        if ($response['ResponseCode'] != 0) {
            return response()->json([
              'success' => false,
              'errors' => ['error' => 'Payment Incomplete'],
            ]);
        }

        $mpesaTransaction = new MpesaTransaction();
        $mpesaTransaction->user_id = auth()->id();
        $mpesaTransaction->media_id = $this->request->id;
        $mpesaTransaction->isMessage = $this->request->isMessage? 1: 0;
        $mpesaTransaction->merchantRequestID = $response['MerchantRequestID'];
        $mpesaTransaction->amount = $amountFixed;
        $mpesaTransaction->amount_usd = $this->request->amount;
        $mpesaTransaction->save();

        $msgId = $this->request->id;

        $media = $this->request->isMessage ? Messages::find($this->request->id) : Updates::find($this->request->id);
        $url = url($media->user()->username, 'post').'/'.$this->request->id;

        if($this->request->isMessage){
          $message = Messages::whereId($mpesaTransaction->media_id)->get();

          $data = view('includes.messages-chat', [
            'messages' => $message,
            'allMessages' => 0,
            'counter' => 0
            ])->render();
        }        

        return response()->json([
          "success" => true,
          "url" => $url,
          "data" => $data ?? false,
          "msgId" => $msgId ?? false,
          "wallet" => Helper::userWallet()
        ]);

        } catch (\Exception $e) {
          return response()->json([
              'success' => false,
              'errors' => ['error' => $e->getMessage()],
          ]);
        }

  }

  public function mpesaPPVIpn(Request $request){

    $response = isset($request['Body']) ? $request['Body'] : [];

      if ($response != null && $response['stkCallback']['MerchantRequestID'] != null) {
          $merchantRequestID      = $response['stkCallback']['MerchantRequestID'];
          $CallbackMetadata       = $response['stkCallback']['CallbackMetadata']['Item'];
          $amount                 = $CallbackMetadata[0]['Value'];
          $mpesaReceiptNumber     = $CallbackMetadata[1]['Value'];

          $mpesaTransaction = MpesaTransaction::where('merchantRequestID', $merchantRequestID)->first();
          
          if($mpesaTransaction && $mpesaTransaction->status == 0){
            $mpesaTransaction->mpesaReceiptNumber = $mpesaReceiptNumber;
            $mpesaTransaction->status = 1;
            $mpesaTransaction->save();

            $payment = PaymentGateways::whereName('Mpesa')->whereEnabled(1)->firstOrFail();

            $media = $mpesaTransaction->isMessage ? Messages::find($mpesaTransaction->media_id) : Updates::find($mpesaTransaction->media_id);

            $earnings = $this->earningsAdminUser($media->user()->custom_fee, $amount, $payment->fee, $payment->fee_cents);

            $this->transaction(
                'ppv_'.str_random(25),
                $media->user()->id,
                0,
                $media->user()->id,
                $mpesaTransaction->amount_usd,
                $earnings['user'],
                $earnings['admin'],
                'Mpesa',
                'ppv',
                $earnings['percentageApplied'],
                $media->user()->taxesPayable()
              );

            $media->user()->increment('balance', $earnings['user']);

            if ($mpesaTransaction->isMessage == 1) {
              $this->payPerViews($media->user()->id, 0, $mpesaTransaction->media_id);
              $url = url('messages', $media->user()->id);

              if ($media->user()->email_new_ppv == 'yes') {
                $this->notifyEmailNewPPV($media->user(), $media->user()->username, $media->message, 'message');
              }

              Notifications::send($media->user()->id, $media->user()->id, '6', $mpesaTransaction->media_id);

              $message = Messages::whereId($mpesaTransaction->media_id)->get();

              $data = view('includes.messages-chat', [
                    'messages' => $message,
                    'allMessages' => 0,
                    'counter' => 0
                    ])->render();

              $msgId = $mpesaTransaction->media_id;

            } else {
              $this->payPerViews($media->user()->id, $mpesaTransaction->media_id, 0);
              $url = url($media->user()->username, 'post').'/'.$mpesaTransaction->media_idd;

              if ($media->user()->email_new_ppv == 'yes') {
                $this->notifyEmailNewPPV($media->user(), $media->user()->username, $media->description, 'post');
              }

              Notifications::send($media->user()->id, $media->user()->id, '7', $mpesaTransaction->media_id);
            }

          }         
          
          http_response_code(200);
      }
    }

  protected function sendStripe()
  {
        // Get Payment Gateway
        $payment = PaymentGateways::whereName('Stripe')->firstOrFail();

        // Check if it is a Message or Post
        $media = $this->request->isMessage ? Messages::find($this->request->id) : Updates::find($this->request->id);

      	$cents  = $this->settings->currency_code == 'JPY' ? Helper::amountGross($this->request->amount) : (Helper::amountGross($this->request->amount)*100);
      	$amount = (int)$cents;
      	$currency_code = $this->settings->currency_code;
      	$description = __('general.unlock_content').' @'.$media->user()->username;

        \Stripe\Stripe::setApiKey($payment->key_secret);

        $intent = null;
        try {
          if (isset($this->request->payment_method_id)) {
            # Create the PaymentIntent
            $intent = \Stripe\PaymentIntent::create([
              'payment_method' => $this->request->payment_method_id,
              'amount' => $amount,
              'currency' => $currency_code,
              "description" => $description,
              'confirmation_method' => 'manual',
              'confirm' => true
            ]);
          }
          if (isset($this->request->payment_intent_id)) {
            $intent = \Stripe\PaymentIntent::retrieve(
              $this->request->payment_intent_id
            );
            $intent->confirm();
          }
          return $this->generatePaymentResponse($intent);
        } catch (\Stripe\Exception\ApiErrorException $e) {
          # Display error on client
          return response()->json([
            'error' => $e->getMessage()
          ]);
        }
  } // End Method sendStripe

  protected function generatePaymentResponse($intent)
  {
    # Note that if your API version is before 2019-02-11, 'requires_action'
    # appears as 'requires_source_action'.
    if (isset($intent->status) && $intent->status == 'requires_action' &&
        $intent->next_action->type == 'use_stripe_sdk') {
      # Tell the client to handle the action
      return response()->json([
        'requires_action' => true,
        'payment_intent_client_secret' => $intent->client_secret,
      ]);
    } else if (isset($intent->status) && $intent->status == 'succeeded') {
      # The payment didn’t need any additional actions and completed!
      # Handle post-payment fulfillment

      // Insert DB
      //========== Processor Fees
      $amount = $this->request->amount;
      $payment = PaymentGateways::whereName('Stripe')->first();

      // Check if it is a Message or Post
      $media = $this->request->isMessage ? Messages::find($this->request->id) : Updates::find($this->request->id);

      // Admin and user earnings calculation
      $earnings = $this->earningsAdminUser($media->user()->custom_fee, $this->request->amount, $payment->fee, $payment->fee_cents);

      // Insert Transaction
      $this->transaction(
          'ppv_'.str_random(25),
          auth()->user()->id,
          0,
          $media->user()->id,
          $this->request->amount,
          $earnings['user'],
          $earnings['admin'],
          'Stripe',
          'ppv',
          $earnings['percentageApplied'],
          auth()->user()->taxesPayable()
        );

      // Add Earnings to User
      $media->user()->increment('balance', $earnings['user']);

       // Check if is sent by message
       if ($this->request->isMessage) {
         // $user_id, $updates_id, $messages_id
         $this->payPerViews(auth()->user()->id, 0, $this->request->id);
         $url = url('messages', $media->user()->id);

         // Send Email Creator
         if ($media->user()->email_new_ppv == 'yes') {
           $this->notifyEmailNewPPV($media->user(), auth()->user()->username, $media->message, 'message');
         }

         // Send Notification - destination, author, type, target
         Notifications::send($media->user()->id, auth()->user()->id, '6', $this->request->id);

         // Get message to show live
         $message = Messages::whereId($this->request->id)->get();

         $data = view('includes.messages-chat', [
        			'messages' => $message,
        			'allMessages' => 0,
        			'counter' => 0
        			])->render();

         $msgId = $this->request->id;

       } else {
         // $user_id, $updates_id, $messages_id
         $this->payPerViews(auth()->user()->id, $this->request->id, 0);
         $url = url($media->user()->username, 'post').'/'.$this->request->id;

         // Send Email Creator
         if ($media->user()->email_new_ppv == 'yes') {
           $this->notifyEmailNewPPV($media->user(), auth()->user()->username, $media->description, 'post');
         }

         // Send Notification - destination, author, type, target
         Notifications::send($media->user()->id, auth()->user()->id, '7', $this->request->id);
       }

      return response()->json([
        "success" => true,
        "url" => $url,
        "data" => $data ?? false,
        "msgId" => $msgId ?? false,
        "wallet" => Helper::userWallet()
      ]);
    } else {
      # Invalid status
      http_response_code(500);
      return response()->json(['error' => 'Invalid PaymentIntent status']);
    }
  }// End generatePaymentResponse

  public function sendPaystack()
  {

    $payment = PaymentGateways::whereName('Paystack')->whereEnabled(1)->firstOrFail();
    $paystack = new Paystack($payment->key_secret);
    $amount = Helper::amountGross($this->request->amount);

    if (isset($this->request->trxref)) {
      try {
        $tranx = $paystack->transaction->verify([
          'reference' => $this->request->trxref,
        ]);
      } catch (\Exception $e) {
        return response()->json([
          "success" => false,
          'errors' => ['error' => $e->getMessage()]
        ]);
      }

      if ('success' === $tranx->data->status) {
        // Verify transaction
        $verifyTxnId = Transactions::where('txn_id', $tranx->data->reference)->first();

      if ( ! isset($verifyTxnId)) {

        // Check if it is a Message or Post
        $media = $this->request->isMessage ? Messages::find($this->request->id) : Updates::find($this->request->id);

        // Admin and user earnings calculation
        $earnings = $this->earningsAdminUser($media->user()->custom_fee, $this->request->amount, $payment->fee, $payment->fee_cents);

        // Insert Transaction
        $this->transaction(
            'ppv_'.str_random(25),
            auth()->user()->id,
            0,
            $media->user()->id,
            $this->request->amount,
            $earnings['user'],
            $earnings['admin'],
            'Paystack',
            'ppv',
            $earnings['percentageApplied'],
            auth()->user()->taxesPayable()
          );

        // Add Earnings to User
        $media->user()->increment('balance', $earnings['user']);

         // Check if is sent by message
         if ($this->request->isMessage) {
           // $user_id, $updates_id, $messages_id
           $this->payPerViews(auth()->user()->id, 0, $this->request->id);
           $url = url('messages', $media->user()->id);

           // Send Email Creator
           if ($media->user()->email_new_ppv == 'yes') {
             $this->notifyEmailNewPPV($media->user(), auth()->user()->username, $media->message, 'message');
           }

           // Send Notification - destination, author, type, target
           Notifications::send($media->user()->id, auth()->user()->id, '6', $this->request->id);

           // Get message to show live
           $message = Messages::whereId($this->request->id)->get();

           $data = view('includes.messages-chat', [
          			'messages' => $message,
          			'allMessages' => 0,
          			'counter' => 0
          			])->render();

           $msgId = $this->request->id;

         } else {
           // $user_id, $updates_id, $messages_id
           $this->payPerViews(auth()->user()->id, $this->request->id, 0);
           $url = url($media->user()->username, 'post').'/'.$this->request->id;

           // Send Email Creator
           if ($media->user()->email_new_ppv == 'yes') {
             $this->notifyEmailNewPPV($media->user(), auth()->user()->username, $media->description, 'post');
           }

           // Send Notification - destination, author, type, target
           Notifications::send($media->user()->id, auth()->user()->id, '7', $this->request->id);
         }

        } // end verifyTxnId

        return response()->json([
          "success" => true,
          'instantPayment' => true,
          "url" => $url,
          "data" => $data ?? false,
          "msgId" => $msgId ?? false,
          "wallet" => Helper::userWallet()
        ]);
      } else {
        return response()->json([
            'success' => false,
            'errors' => ['error' => $tranx->data->gateway_response],
        ]);
      }


    } else {
      return response()->json([
          'success' => true,
          'insertBody' => "<script type='text/javascript'>var handler = PaystackPop.setup({
            key: '".$payment->key."',
            email: '".auth()->user()->email."',
            amount: ".($amount*100).",
            currency: '".$this->settings->currency_code."',
            ref: '".Helper::genTranxRef()."',
            callback: function(response) {
              var input = $('<input type=hidden name=trxref />').val(response.reference);
              $('#formSendPPV').append(input);
              $('#ppvBtn').trigger('click');
            },
            onClose: function() {
                alert('Window closed');
            }
          });
          handler.openIframe();</script>"
      ]);
    }
  }// end method

  public function sendCCbill()
	{
		// Get Payment Gateway
		$payment = PaymentGateways::whereName('CCBill')->firstOrFail();

		$currencyCodes = [
			'AUD' => 036,
			'CAD' => 124,
			'JPY' => 392,
			'GBP' => 826,
			'USD' => 840,
			'EUR' => 978
		];

    $formPrice = number_format(Helper::amountGross($this->request->amount), 2);

		$formInitialPeriod = 2;
		$currencyCode = array_key_exists($this->settings->currency_code, $currencyCodes) ? $currencyCodes[$this->settings->currency_code] : 840;

		// Hash
		$hash = md5($formPrice . $formInitialPeriod . $currencyCode . $payment->ccbill_salt);

		$input['clientAccnum']  = $payment->ccbill_accnum;
		$input['clientSubacc']  = $payment->ccbill_subacc;
		$input['currencyCode']  = $currencyCode;
		$input['formDigest']    = $hash;
		$input['initialPrice']  = $formPrice;
		$input['initialPeriod'] = $formInitialPeriod;
		$input['type']          = 'ppv';
		$input['isMessage']     = $this->request->isMessage ?? null;
		$input['media']         = $this->request->id;
		$input['user']          = auth()->user()->id;
    $input['priceOriginal'] = $this->request->amount;
    $input['taxes']         = auth()->user()->taxesPayable();

		// Base url
		$baseURL = 'https://api.ccbill.com/wap-frontflex/flexforms/' . $payment->ccbill_flexid;

		// Build redirect url
		$inputs = http_build_query($input);
		$redirectUrl = $baseURL . '?' . $inputs;

		return response()->json([
								'success' => true,
								'url' => $redirectUrl,
						]);

	}// End Method


}
