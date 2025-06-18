<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
   
    public function logout(Request $request)
    {
        // Get the authenticated user BEFORE logging them out
        $userType = null;
        if (Auth::check()) {
            $userType = Auth::user()->type;
        }

        // Perform the actual logout
        Auth::guard('web')->logout(); // Specify the guard if you have multiple
        
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        // Redirect based on user type
        switch ($userType) {
            case 'super':
                return redirect()->route('adminlogin')->with('message', 'You have been logged out as Super Admin.');
            case 'admin':
                return redirect()->route('login')->with('message', 'You have been logged out as Admin.');
            case 'agent':
                return redirect()->route('login')->with('message', 'You have been logged out as Agent.');
            default:
                // Fallback for unknown type or if userType couldn't be determined (e.g., already logged out)
                return redirect()->route('login')->with('message', 'You have been logged out.');
        }
    }
}