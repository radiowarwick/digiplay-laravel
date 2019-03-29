<?php

namespace App\Http\Middleware;

use Closure;
use App\ApiApplication;

class Api
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission_string = '') {
        if($key = $request->get('key')) {
            if(count(ApiApplication::where('key', $key)->get()) > 0) {
                $response = $next($request);
                // As this is an API page we'll allow access from anywhere
                $response->headers->set('Access-Control-Allow-Origin', '*');
                return $response;
            }
        }
        return abort(403, 'Key Invalid');
    }
}