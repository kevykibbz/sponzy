<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Models\AdminSettings;

class PrivateContent
{
	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	public function __construct(Guard $auth, AdminSettings $settings)
	{
		$this->settings = $settings::first();
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

			if ($this->auth->guest() && $this->settings->who_can_see_content == 'users') {
 			 session()->flash('login_required', true);
 				 return $this->settings->home_style == 0 ? redirect()->route('login') : redirect()->route('home');
 			 }

		return $next($request);
	}

}
