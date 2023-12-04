<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Traits\Functions;
use App\Helper;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, Functions;
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
      $this->auth = $auth;
      $this->middleware('guest')->except('logout');
    }

    /**
     * Show login form.
     */
    public function showLoginForm()
    {
  		if (config('settings.home_style') == 0)	{
  			return view('auth.login');
  		} else {
  			return redirect('/');
  		}
    }

    public function login(Request $request)
    {
      if (! $request->expectsJson()) {
          abort(404);
      }

      $request['_captcha'] = config('settings.captcha');

      $messages = [
    'g-recaptcha-response.required_if' => trans('admin.captcha_error_required'),
    'g-recaptcha-response.captcha' => trans('admin.captcha_error'),
  ];

  	     // get our login input
      $login = $request->input('username_email');
      $urlReturn = $request->input('return');
      $isModal = $request->input('isModal');

      // check login field
      $login_type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

      // merge our login field into the request with either email or username as key
      $request->merge([$login_type => $login]);

      // let's validate and set our credentials
      if ($login_type == 'email') {

          $validator = Validator::make($request->all(), [
              'username_email'    => 'required|email',
              'password' => 'required',
              'g-recaptcha-response' => 'required_if:_captcha,==,on|captcha'
          ], $messages);

          if ($validator->fails()) {
   		        return response()->json([
   				        'success' => false,
   				        'errors' => $validator->getMessageBag()->toArray()
   				    ]);
   		    }

          $credentials = $request->only('email', 'password');

      } else {

          $validator = Validator::make($request->all(), [
              'username_email' => 'required',
              'password' => 'required',
              'g-recaptcha-response' => 'required_if:_captcha,==,on|captcha'
          ], $messages);

          if ($validator->fails()) {
   		        return response()->json([
   				        'success' => false,
   				        'errors' => $validator->getMessageBag()->toArray(),
   				    ]);
   		    }

          $credentials = $request->only('username', 'password');

      }

    if ($this->auth->attempt($credentials, $request->has('remember'))) {

  			if ($this->auth->user()->status == 'active') {

          // Check Two step authentication
          if ($this->auth->user()->two_factor_auth == 'yes') {
            // Generate code...
            $this->generateTwofaCode($this->auth->user());

            // Logout user
            $this->auth->logout();

            return response()->json([
                'actionRequired' => true,
            ]);
          }

          // Insert Login Session
          $this->loginSession($this->auth->user()->id);

              if (isset($urlReturn) && url()->isValidUrl($urlReturn) && Helper::checkSourceURL($urlReturn)) {
                return response()->json([
                    'success' => true,
                    'isLoginRegister' => true,
                    'isModal' => $isModal ? true : false,
                    'url_return' => $urlReturn
                ]);
                } else {
                  return response()->json([
                      'success' => true,
                      'isLoginRegister' => true,
                      'isModal' => $isModal ? true : false,
                      'url_return' => url('/')
                  ]);
                }

          } else if ($this->auth->user()->status == 'suspended') {

  			$this->auth->logout();

        return response()->json([
            'success' => false,
            'errors' => ['error' => trans('validation.user_suspended')],
        ]);

      } else if ($this->auth->user()->status == 'pending') {

  			$this->auth->logout();

        return response()->json([
            'success' => false,
            'errors' => ['error' => trans('validation.account_not_confirmed')],
        ]);
      }
    }

    return response()->json([
        'success' => false,
        'errors' => ['error' => trans('auth.failed')]
    ]);
  }

}
