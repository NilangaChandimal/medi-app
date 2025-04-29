<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AdminResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.admin-passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $response = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->password = Hash::make($password);
                $admin->setRememberToken(Str::random(60));
                $admin->save();
            }
        );

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('success', 'Password reset successfully!')
            : back()->withErrors(['email' => trans($response)]);
    }
}

