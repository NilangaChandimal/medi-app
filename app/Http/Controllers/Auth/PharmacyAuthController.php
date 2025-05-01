<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PharmacyAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.pharmacy-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('pharmacy')->attempt($credentials)) {
            $pharmacy = Auth::guard('pharmacy')->user();


        if ($pharmacy->is_blocked == 1) {
            Auth::guard('pharmacy')->logout();
            return redirect()->back()->with('blocked', 'Your account has been Tempory blocked contact the hotline!!');
        }

        if ($pharmacy->status == "inactive") {
            Auth::guard('pharmacy')->logout();
            return redirect()->back()->with('checked', 'Your account not checked, please wait until check your account!!');
        }
            return redirect()->intended(route('pharmacy.home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.pharmacy-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pharmacies',
            'registration_number' => 'required|unique:pharmacies',
            'license_details' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|string',
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('profile_image');
            $filename = time() . '.' . $profile_image->getClientOriginalExtension();
            $profile_image->move(public_path('profile_image'), $filename);
            Log::info('Image uploaded');
        } else {
            $filename = 'default.jpg';
        }

        $pharmacy = Pharmacy::create([
            'name' => $request->name,
            'email' => $request->email,
            'registration_number' => $request->registration_number,
            'license_details' => $request->license_details,
            'address' => $request->address,
            'phone' => $request->phone,
            'city' => $request->city,
            'profile_image' => $filename,
            'password' => bcrypt($request->password),
            'status' => 'inactive',
        ]);

        Auth::guard('pharmacy')->login($pharmacy);

        return redirect()->route('pharmacy.login');
    }

    public function logout(Request $request)
    {
        Auth::guard('pharmacy')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('pharmacy.login');
    }

    public function edit()
{
    return view('pharmacy.profile.edit', ['pharmacy' => auth()->user()]);
}

public function update(Request $request)
{
    $pharmacy = auth()->user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:pharmacies,email,'.$pharmacy->id,
        'phone' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string',
        'password' => 'nullable|string|min:8|confirmed',
        'profile_image' => 'nullable|image|max:2048',
        'old_password' => [
            'nullable',
            function ($attribute, $value, $fail) use ($pharmacy) {
                if (!Hash::check($value, $pharmacy->password)) {
                    $fail('The old password is incorrect.');
                }
            },
        ],
    ]);

    if ($request->hasFile('profile_image')) {
        if ($pharmacy->profile_image) {
            Storage::delete($pharmacy->profile_image);
        }
        $validated['profile_image'] = $request->file('profile_image')->store('pharmacy-profile');
    }
    unset($validated['old_password']);
    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $pharmacy->update($validated);

    return redirect()->route('pharmacy.profile.edit')->with('success', 'Profile updated successfully');
}
}
