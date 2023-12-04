<?php

namespace App\Http\Controllers;

use Socialite;
use Illuminate\Http\Request;
use App\Services\SocialAccountService;

class SocialAuthController extends Controller
{
  // Redirect function
  public function redirect($provider)
  {
    return Socialite::driver($provider)->redirect();
  }
  // Callback function
  public function callback(SocialAccountService $service, Request $request, $provider)
  {
    try {
      $user = $service->createOrGetUser(Socialite::driver($provider)->user(), $provider);

      // Return Error missing Email User
      if (!isset($user->id)) {
        return $user;
      } else {
        auth()->login($user);
      }
    } catch (\Exception $e) {
      return redirect('login')->with(['login_required' => $e->getMessage()]);
    }

    return redirect()->to('/');
  }
}
