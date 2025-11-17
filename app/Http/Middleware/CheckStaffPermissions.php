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
                // Can add/edit/delete staff
                if (!$user->canManageStaff()) {
                    abort(403, 'You do not have permission to manage staff.');
                }
                break;
                
            case 'view':
                // Can view staff pages
                if (!$user->canViewStaff()) {
                    abort(403, 'You do not have permission to view staff.');
                }
                break;
                
            case 'view_inventory':
                // Can view inventory
                if (!$user->canViewInventory()) {
                    abort(403, 'You do not have permission to view inventory.');
                }
                break;
                
            case 'manage_inventory':
                // Can manage inventory
                if (!$user->canManageInventory()) {
                    abort(403, 'You do not have permission to manage inventory.');
                }
                break;
        }

        return $next($request);
    }
}