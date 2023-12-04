<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use App\Models\User;

class UserOnline
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   */
  public function handle($request, Closure $next): mixed
  {
    try {
      if (auth()->check()) {
        $expiresAt = now()->addMinutes(1);
        Cache::put('is-online-' . auth()->id(), true, $expiresAt);

        if (!$request->is('messages/*') || $request->route()->getName() != 'live.data') {
          // last seen
          User::whereId(auth()->id())->update([
            'last_seen' => (new \DateTime())->format('Y-m-d H:i:s')
          ]);
        }
      }
    } catch (\Exception $e) {
    }

    return $next($request);
  }
}
