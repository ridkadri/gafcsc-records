<?php
// app/Http/Middleware/ForcePasswordChange.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Skip if no user or already on password change routes
        if (!$user || $request->routeIs('password.change') || $request->routeIs('logout')) {
            return $next($request);
        }
        
        // Check if password needs to be changed
        if ($user->needsPasswordChange()) {
            return redirect()->route('password.change')
                ->with('warning', 'You must change your default password before continuing.');
        }
        
        return $next($request);
    }
}