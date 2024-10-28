<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permissionId, $requiredPermission)
    {
        // Check if the user is logged in
        if (Auth::check()) {
            // Get the user's permissions
            $userPermissions = Auth::user()->getPermissions();

            // Check if the user has the required permission using bitwise operation
            if (isset($userPermissions[$permissionId]) && ($userPermissions[$permissionId]->permission_value & $requiredPermission)) {
                // Proceed to the next request
                return $next($request);
            }
        }

        // If the user does not have permission, redirect or show an error
        return redirect()->route('404')->with('error', 'You do not have permission to access this page.');
    }
}
