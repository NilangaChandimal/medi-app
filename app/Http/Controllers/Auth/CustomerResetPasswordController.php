<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class CustomerResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.customer-passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $response = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($customer, $password) {
                $customer->password = Hash::make($password);
                $customer->setRememberToken(Str::random(60));
                $customer->save();
            }
        );

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('success', 'Password reset successfully!')
            : back()->withErrors(['email' => trans($response)]);
    }
}

