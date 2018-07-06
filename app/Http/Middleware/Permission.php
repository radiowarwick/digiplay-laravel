<?php

namespace App\Http\Middleware;

use Closure;

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
        if(auth()->check()) {
            $groups = auth()->user()->groups;
            foreach($groups as $group) {
                // Always allow if the user is an admin
                if($group->name === "Admin")
                    return $next($request);
                // Otherwise check each of the user's groups for the correct permission
                else {
                    foreach($group->permissions as $permission) {
                        if($permission->name === $permission_string)
                            return $next($request);
                    }
                }
            }
            abort(403, 'Permission Denied');
        }
        else {
            abort(403, 'Permission Denied');
        }
    }
}
