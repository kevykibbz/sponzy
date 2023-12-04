<?php

namespace App\Http\Controllers\Auth;

use Mail;
use Cookie;
use Validator;
use App\Helper;
use App\Models\User;
use App\Models\Countries;
use App\Models\Referrals;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Traits\Functions;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, Functions;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminSettings $settings)
    {
        $this->middleware('guest');
        $this->settings = $settings::first();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

      $data['_captcha'] = $this->settings->captcha;

		$messages = array (
			"letters"    => trans('validation.letters'),
      'g-recaptcha-response.required_if' => trans('admin.captcha_error_required'),
      'g-recaptcha-response.captcha' => trans('admin.captcha_error'),
        );

		 Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		// Validate if have one letter
	Validator::extend('letters', function($attribute, $value, $parameters){
    	return preg_match('/[a-zA-Z0-9]/', $value);
	});

        return Validator::make($data, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'agree_gdpr' => 'required',
            'g-recaptcha-response' => 'required_if:_captcha,==,on|captcha'
        ], $messages);
    }

    /**
     * Show registration form.
     */
    public function showRegistrationForm()
    {
  		if ($this->settings->registration_active == '1' && $this->settings->home_style == 0)	{
  			return view('auth.register');
  		} else {
  			return redirect('/');
  		}
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
      if ($this->settings->account_verification) {
        $verify = 'no';
      } else {
        $verify = 'yes';
      }

      $token = Str::random(75);

      // Get user country
      $country = Countries::whereCountryCode(Helper::userCountry())->first();

      return User::create([
        'username'          => Helper::strRandom(),
        'countries_id'      => $country->id ?? '',
        'name'              => $data['name'],
        'email'             => strtolower($data['email']),
        'password'          => bcrypt($data['password']),
        'avatar'            => $this->settings->avatar,
        'cover'             => $this->settings->cover_default ?? '',
        'status'            => $this->settings->email_verification ? 'pending' : 'active',
        'role'              => 'normal',
        'permission'        => 'none',
        'confirmation_code' => '',
        'oauth_uid'         => '',
        'oauth_provider'    => '',
        'token'             => $token,
        'story'             => trans('users.story_default'),
        'verified_id'       => $verify,
        'ip'                => request()->ip(),
        'language'          => session('locale'),
        'hide_name'         => 'yes',
      ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        $isModal   = $request->input('isModal');

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray(),
            ]);
        }

        // Verify Settings Admin
        if ($this->settings->email_verification) {

          $isProfile = isset($request->isProfile) ? '?r='.$request->isProfile : null;

          $confirmation_code = Str::random(100);

        //send verification mail to user
        $_username      = $request->name;
        $_email_user    = $request->email;
        $_title_site    = $this->settings->title;
        $_email_noreply = $this->settings->email_no_reply;

        try {
          Mail::send('emails.verify', ['confirmation_code' => $confirmation_code, 'isProfile' => $isProfile],
          function($message) use (
              $_username,
              $_email_user,
              $_title_site,
              $_email_noreply
          ) {
              $message->from($_email_noreply, $_title_site);
              $message->subject(trans('users.title_email_verify'));
              $message->to($_email_user,$_username);
            });
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());

            return response()->json([
              'success' => false,
              'errors' => ['error' => 'Failed to authenticate on SMTP server']
          ]);
        } 

        } else {
          $confirmation_code = '';
        }

        event(new Registered($user = $this->create($request->all())));

        // Check Referral
        if ($this->settings->referral_system == 'on') {

          $referredBy = User::find(Cookie::get('referred'));

          if ($referredBy) {
            Referrals::create([
              'user_id' => $user->id,
              'referred_by' => $referredBy->id,
            ]);
          }
        }

        // Update Username
        $user->update([
          'username' => Helper::createUsername($user->name, $user->id),
          'confirmation_code' => $confirmation_code
        ]);

        // Verify Settings Admin
    		if ($this->settings->email_verification) {
          return response()->json([
              'success' => true,
              'check_account' => trans('auth.check_account'),
          ]);

        } else {

          // Insert Login Session
          $this->loginSession($user->id);

          if ($this->settings->autofollow_admin) {
            // Auto-follow Admin
            $this->autoFollowAdmin($user->id);
          }

            $this->guard()->login($user);

            return response()->json([
                'success' => true,
                'isLoginRegister' => true,
                'isModal' => $isModal ? true : false,
                'url_return' => url('settings/page'),
            ]);
        }

    }
}
