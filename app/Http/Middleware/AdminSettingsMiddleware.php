<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AdminSettings;

class AdminSettingsMiddleware
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
        try {
            $model = AdminSettings::first();
            $data = $model->attributesToArray();

            foreach ($data as $key => $value) {
                config(['settings.' . $key => $value]);
            }

        } catch (\Exception $e) {}
        
        return $next($request);
    }
}
