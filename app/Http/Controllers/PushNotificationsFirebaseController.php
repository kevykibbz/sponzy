<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Helper;

class PushNotificationsFirebaseController extends Controller
{
  use Traits\Functions;

  public function __construct(Request $request, AdminSettings $settings)
  {
    $this->request = $request;
    $this->settings = $settings::first();
  }

  /**
   * Save tokeb Firebase
   *
   * @return response()
   */
  public function saveTokenFirebase(Request $request)
  {
      auth()->user()->update(['device_token' => $request->token]);
      return response()->json(['token saved successfully.']);
  }

  /**
   * Send Notification Firebase
   *
   * @return response()
   */
  public function sendNotificationFirebase(Request $request)
  {
      $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

      $SERVER_API_KEY = 'AAAAwQEqBCQ:APA91bE3Y8k_zlgecQlUHiZCkURWmuY_Iwvo7qJLYzTUtKr6-KCiEvp3wsK2hPtKJpN6LXk4pZYcb8LIaG4nCX7mWsVtcO88iZ2qOPp6Q2w2nwIOx_b3u3jZWGcfmdHc2YET3I0ScfL8';

      $data = [
          "registration_ids" => $firebaseToken,
          "notification" => [
              "title" => 'Sponzy',
              "body" => 'Miguel has sent you a tip',
              "click_action" => url('notifications')
          ]
      ];
      $dataString = json_encode($data);

      $headers = [
          'Authorization: key=' . $SERVER_API_KEY,
          'Content-Type: application/json',
      ];

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

      $response = curl_exec($ch);

      dd($response);
  }
}
