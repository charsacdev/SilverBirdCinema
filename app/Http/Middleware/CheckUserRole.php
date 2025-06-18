<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$type): Response
    {
        if (!Auth::check()) {
            // If user is not logged in, redirect to login page
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If no type are specified, just proceed (e.g., for general 'auth' where role isn't strictly checked)
        if (empty($type)) {
            return $next($request);
        }

        // Check if the user's role is in the allowed type list
        if (in_array($user->type, $type)) {
            return $next($request);
        }

        // If the user's role is not allowed, redirect or abort
        // You can customize the redirect or error message
        Auth::logout(); // Log out the unauthorized user for security
        return redirect()->route('login')->with('error', 'You do not have permission to access this page.');
        // Or abort(403, 'Unauthorized access.');
    }
}