<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission_string = '') {
        if($this->hasPermission($permission_string))
            return $next($request);
        else
            abort(403, 'Permission Denied');
    }

    public static function hasPermission($permission_string = '') {
        if(Auth::check()) {
            return Auth::user()->hasPermission($permission_string);
        }
        return false;
    }
}
