<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LiveOnlineUsers;
use App\Models\LiveStreamings;

class OnlineUsersLive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
      if (auth()->check()) {

        // Update Online users
        LiveOnlineUsers::whereUserId(auth()->id())
        ->whereLiveStreamingsId($request->live_id)
        ->update([
          'updated_at' => now()
        ]);

        if ($request->creator == auth()->id()) {
          LiveStreamings::whereId($request->live_id)
            ->whereUserId($request->creator)
            ->update([
              'updated_at' => now()
            ]);
        }

      }
        return $next($request);
    }
}
