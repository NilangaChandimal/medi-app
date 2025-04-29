<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.customer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($credentials)) {
            $customer = Auth::guard('customer')->user();

            // Check if the customer is blocked

        if ($customer->is_blocked == 1) {
            // Log out and redirect with an error if blocked
            Auth::guard('customer')->logout();
            return redirect()->back()->with('blocked', 'Your account has been Tempory blocked contact the hotline!!');
        }
            return redirect()->intended(route('customer.home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.customer-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pharmacies',
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

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'city' => $request->city,
            'profile_image' => $filename,
            'password' => bcrypt($request->password),
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('customer.home');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customer.login');
    }

    public function edit()
{
    return view('customer.profile.edit', ['customer' => auth()->user()]);
}

public function update(Request $request)
{
    $customer = auth()->user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email,'.$customer->id,
        'phone' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string',
        'password' => 'nullable|string|min:8|confirmed',
        'profile_image' => 'nullable|image|max:2048',
    ]);

    // Handle profile image
    if ($request->hasFile('profile_image')) {
        if ($customer->profile_image) {
            Storage::delete($customer->profile_image);
        }
        $validated['profile_image'] = $request->file('profile_image')->store('customer-profile');
    }

    // Update password if provided
    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $customer->update($validated);

    return redirect()->route('customer.profile.edit')->with('success', 'Profile updated successfully');
}
}
