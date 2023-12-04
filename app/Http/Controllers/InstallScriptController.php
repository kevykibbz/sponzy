<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\AdminSettings;
use App\Helper;
use Carbon\Carbon;

class InstallScriptController extends Controller
{

  public function requirements()
  {
    try {
      // Check Datebase
       $settings = AdminSettings::first();
       return redirect('/');
    } catch (\Exception $e) {
      // empty
    }

    $minVersionPHP     = '8.1.0';
    $currentVersionPHP = (int) str_replace('.', '', phpversion());
    $versionPHP = version_compare(phpversion(), $minVersionPHP, '>=') ? true : false;

    // Extensions
    $dom       =  extension_loaded('dom') ? true : false;
    $Ctype     =  extension_loaded('Ctype') ? true : false;
    $Fileinfo  =  extension_loaded('Fileinfo') ? true : false;
    $openssl   =  extension_loaded('openssl') ? true : false;
    $pdo       =  extension_loaded('pdo') ? true : false;
    $mbstring  =  extension_loaded('mbstring') ? true : false;
    $tokenizer =  extension_loaded('tokenizer') ? true : false;
    $hash      =  extension_loaded('hash') ? true : false;
    $xml       =  extension_loaded('XML') ? true : false;
    $curl      =  extension_loaded('cURL') ? true : false;
    $gd        =  extension_loaded('gd') ? true : false;
    $exif      =  extension_loaded('exif') ? true : false;
    $session   =  extension_loaded('session') ? true : false;
    $filter   =  extension_loaded('filter') ? true : false;
    $allow_url_fopen = ini_get('allow_url_fopen') ? true : false;
    $pcre = extension_loaded('pcre') ? true : false;

    return view('installer.requirements', [
      'versionPHP' => $versionPHP,
      'minVersionPHP' => $minVersionPHP,
      'dom' => $dom,
      'Ctype' => $Ctype,
      'Fileinfo' => $Fileinfo,
      'openssl' => $openssl,
      'pdo' => $pdo,
      'mbstring' => $mbstring,
      'tokenizer' => $tokenizer,
      'hash' => $hash,
      'xml' => $xml,
      'curl' => $curl,
      'gd' => $gd,
      'exif' => $exif,
      'session' => $session,
      'filter' => $filter,
      'allow_url_fopen' => $allow_url_fopen,
      'pcre' => $pcre
    ]);
  }

  public function database()
  {
    try {
      // Check Datebase
       $settings = AdminSettings::first();
       return redirect('/');
    } catch (\Exception $e) {
      // empty
    }

    return view('installer.database');
  }

    public function store(Request $request)
    {
      try {
        // Check Datebase
         $settings = AdminSettings::first();
         return redirect('/');
      } catch (\Exception $e) {
        // empty
      }

      $data = $request->validate([
        'database'     => 'required|string|max:50',
        'username'     => 'required|string|max:50',
        'host'         => 'required|string|max:70',
        'app_name'     => 'required|string|max:50',
        'app_url'      => 'required|url',
        'email_admin'  => 'required|email',
      ]);

      // Database
      Helper::envUpdate('DB_DATABASE', $request->database);
      Helper::envUpdate('DB_USERNAME', $request->username);
      Helper::envUpdate('DB_HOST', $request->host);
      Helper::envUpdate('DB_PASSWORD', ' "'.$request->password.'" ', true);

      // App
  		Helper::envUpdate('APP_NAME', ' "'.$request->app_name.'" ', true);
      Helper::envUpdate('APP_URL', trim($request->app_url, '/'));
      Helper::envUpdate('MAIL_FROM_ADDRESS', $request->email_admin);

      \Artisan::call('cache:clear');
      \Artisan::call('config:cache');
      \Artisan::call('config:clear');

      Auth::loginUsingId(1);

      // Update registration date
      $user = Auth::user();
      $user->date = Carbon::now();
      $user->save();

      return redirect('panel/admin');
    }

}
