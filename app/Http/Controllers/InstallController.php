<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentGateways;
use App\Models\User;
use App\Models\AdminSettings;

class InstallController extends Controller
{
    public function __construct() {
      $this->middleware('role');
    }

    public function install($addon)
    {

      //<-------------- Install --------------->
      if($addon == 'mollie') {

        $verifyPayment = PaymentGateways::whereName('Mollie')->first();

        if(!$verifyPayment) {

          // Controller
          $filePathController = 'mollie-payment/MollieController.php';
          $pathController = app_path('Http/Controllers/MollieController.php');

          if ( \File::exists($filePathController) ) {
            rename($filePathController, $pathController);
          }//<--- IF FILE EXISTS

          // View
          $filePathView = 'mollie-payment/mollie-settings.blade.php';
          $pathView = resource_path('views/admin/mollie-settings.blade.php');

          if ( \File::exists($filePathView) ) {
            rename($filePathView, $pathView);
          }//<--- IF FILE EXISTS


          file_put_contents(
              'routes/web.php',
              "\nRoute::get('payment/mollie', 'MollieController@show')->name('mollie');\nRoute::post('webhook/mollie', 'MollieController@webhook');\n",
              FILE_APPEND
          );

          if(Schema::hasTable('payment_gateways')) {
              \DB::table('payment_gateways')->insert(
      				[
      					'name' => 'Mollie',
      					'type' => 'normal',
      					'enabled' => '0',
      					'fee' => 3.0,
      					'fee_cents' => 0.00,
      					'email' => '',
      					'key' => '',
      					'key_secret' => '',
      					'bank_info' => '',
      					'token' => str_random(150),
      			]
          );
        }

        $indexPath = 'mollie-payment/index.php';
        unlink($indexPath);

        rmdir('mollie-payment');

        $getPayment = PaymentGateways::whereName('Mollie')->firstOrFail();

          return redirect('panel/admin/payments/'.$getPayment->id);
        } else {
          return redirect('/');
        }

    }
  }//<---------------------- End Install

}
