<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckBlocked
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->blocked) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['Your account has been blocked. Please contact support.']);
            }
        }

        return $next($request);
    }
}