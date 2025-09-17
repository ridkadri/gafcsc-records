<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStaffPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        switch ($permission) {
            case 'manage':
                if (!$user->canManageStaff()) {
                    abort(403, 'You do not have permission to manage staff.');
                }
                break;
            case 'view':
                if (!$user->canViewStaff()) {
                    abort(403, 'You do not have permission to view staff.');
                }
                break;
        }

        return $next($request);
    }
}