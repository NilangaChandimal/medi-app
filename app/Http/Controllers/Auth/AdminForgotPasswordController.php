<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AdminForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.admin-passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $response = Password::broker('admins')->sendResetLink(
        $request->only('email')
    );

    return $response == Password::RESET_LINK_SENT
        ? back()->with('success', 'We have emailed your password reset link!')
        : back()->withErrors(['email' => trans($response)]);
}

}

