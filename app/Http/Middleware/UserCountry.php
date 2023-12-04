<?php

namespace App\Http\Middleware;

use Closure;
use Cache;
use App\Helper;

class UserCountry
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
      if (! $request->expectsJson()) {
          try {
            $ip = request()->ip();
            if (! Cache::has('userCountry-'.$ip)) {

              $data = Helper::getDatacURL("http://ip-api.com/json/".$ip);

              Cache::put('userCountry-'.$ip, $data->countryCode);
              Cache::put('userRegion-'.$ip, $data->region);
            }

          } catch (\Exception $e) {}
      }// expectsJson

        return $next($request);
    }
}
