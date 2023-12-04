<?php

namespace App\Http\Controllers\Traits;

use App\Helper;
use App\Models\AdminSettings;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;


trait PushNotificationTrait 
{
	// START
	public static function sendPushNotification($msg, $url, array $devices)
	{
        $settings = AdminSettings::select('title', 'onesignal_appid', 'onesignal_restapi')->first();
        $client = new HttpClient();

        $appId      = $settings->onesignal_appid;
        $restApiKey = $settings->onesignal_restapi;

        $headings = [
            "en" => $settings->title
        ];
        
        $content = [
            "en" => $msg
        ];
        
        $response = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
            'headers' => [
                'Authorization' => 'Basic ' . $restApiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
              ],
            'body' => json_encode([
                'app_id' => $appId,
                'contents' => $content,
                'headings' => $headings,
                'include_player_ids' => $devices,
                'data' => array("foo" => "bar"),
                'url' => $url
            ])
        ]);

        return $response;   

    }// End Method

}// End Class
