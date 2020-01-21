<?php

namespace App\Http\Middleware;

use App\Role;
use Closure;
use Illuminate\Support\Facades\Gate;
use mysql_xdevapi\Collection;
use phpDocumentor\Reflection\Types\Array_;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        //authenticated user fetch
        $user = \Auth::user();
        $permissionsArray=array();
//first checking whether the app is running in console and authenticated user
        if (!app()->runningInConsole() && $user) {
            $roles = Role::with('permissions')->get();

            foreach ($roles as $role) {
                foreach ($role->permissions as $permissions) {
                    $permissionsArray[$permissions->title][] = $role->id;
                }

            }

            foreach ($permissionsArray as $title => $roles) {
                Gate::define($title, function (\App\User $user) use ($roles) {
                    return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
                });
            }
        }


        return $next($request);
    }
}
