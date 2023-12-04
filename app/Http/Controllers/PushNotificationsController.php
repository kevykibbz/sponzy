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
use App\Models\UserDevices;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use GuzzleHttp\Client as HttpClient;

class PushNotificationsController extends Controller
{
  use Traits\Functions, Traits\PushNotificationTrait;

  public function __construct(Request $request, AdminSettings $settings)
  {
    $this->request  = $request;
    $this->settings = $settings::select('onesignal_appid', 'onesignal_restapi')->first();
  }

  /**
   * Register device
   *
   * @return UserDevices()
   */
  public function registerDevice()
  {
    if (! $this->request->player_id) {
        return false;
    }
    
    try {

      $device = $this->getDevice($this->request->player_id);

      $getDeviceExists = UserDevices::wherePlayerId($this->request->player_id)
        ->where('user_id', '<>', auth()->id())
        ->first();

        if ($getDeviceExists) {
          $getDeviceExists->delete();
        }

        UserDevices::updateOrCreate([
            'user_id' => $this->request->user_id,
            'player_id' => $this->request->player_id,
            'device_type' => $device,
        ], [
            'user_id' => $this->request->user_id,
            'player_id' => $this->request->player_id,
            'device_type' => $device
        ]); 
    } catch (Exception $e) {
        throw new UnprocessableEntityHttpException($e->getMessage());
    }
  }

  /**
   * Get device
   *
   * @return $device_type
   */
  public function getDevice($playerId)
  {
    $appId      = $this->settings->onesignal_appid;
    $restApiKey = $this->settings->onesignal_restapi;

    $client = new HttpClient();
    $response = $client->request('GET', "https://onesignal.com/api/v1/players/$playerId?app_id=$appId", [
      'headers' => [
        'Authorization' => 'Basic ' . $restApiKey,
        'Content-Type' => 'application/json; charset=utf-8',
        'accept' => 'text/plain',
      ],
    ]);

    $data = json_decode($response->getBody());

    return $data->device_type;
  }

  /**
   * Delete device
   *
   * @return void
   */
  public function deleteDevice()
  {
    try {
        UserDevices::wherePlayerId($this->request->player_id)->delete();
    } catch (Exception $e) {
        throw new UnprocessableEntityHttpException($e->getMessage());
    }
  }
  
}
