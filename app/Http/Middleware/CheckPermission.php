<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->roles->count() > 0) {
                $permissions = auth()->user()->roles[0]->permissions;
            }
            else{
                return response()->json(['Unauthorized' => "No Roles Assigned"])->setStatusCode(401);
            }

            $permissionExists = false;

            foreach ($permissions as $permissions) {
                if ($permissions->name === $permission) {
                    $permissionExists = true;
                    break; // You found the desired permission, so no need to continue the loop
                }
            }

            if ($permissionExists) {
                return $next($request);
            } else {
                return response()->json(['Unauthorized' => "Unauthorized Route"])->setStatusCode(401);
            }

        }

        return response()->json(['Unauthorized' => "Unauthenticated"])->setStatusCode(401);
    }
}