<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use App\Helper;

class Referred
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if (auth()->guest() && $request->has('ref')) {
        Cookie::queue('referred', $request->query('ref'), 60);
      }
        return $next($request);
    }
}
