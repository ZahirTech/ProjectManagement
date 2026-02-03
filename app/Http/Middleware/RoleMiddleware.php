<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Example: ->middleware('role:admin,superadmin')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Check if user's role matches any allowed roles
        if (!in_array($user->role, $roles)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
