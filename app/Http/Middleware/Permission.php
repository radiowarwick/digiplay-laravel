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

    public static function hasPermission($permission_string = '', $skip_auth = false) {
        if(Auth::check() or $skip_auth) {
            $groups = Auth::user()->groups;
            foreach($groups as $group) {
                // Always allow if the user is an admin
                if($group->name === 'Admin')
                    return true;
                // Otherwise check each of the user's groups for the correct permission
                else {
                    foreach($group->permissions as $permission) {
                        if($permission->name === $permission_string)
                            return true;
                    }
                }
            }
        }
        return false;
    }
}
