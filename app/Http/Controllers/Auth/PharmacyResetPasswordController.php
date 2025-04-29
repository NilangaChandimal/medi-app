<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class PharmacyResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.pharmacy-passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $response = Password::broker('pharmacies')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($pharmacy, $password) {
                $pharmacy->password = Hash::make($password);
                $pharmacy->setRememberToken(Str::random(60));
                $pharmacy->save();
            }
        );

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('pharmacy.login')->with('success', 'Password reset successfully!')
            : back()->withErrors(['email' => trans($response)]);
    }
}

