<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
{
    // Get the currently authenticated admin
    $admin = auth()->guard('admin')->user(); // Use the 'admin' guard

    if ($admin) {
        return view('admin.home', compact('admin'));
    }

    return redirect()->route('admin.login')->with('error', 'You must be logged in to view the admin dashboard.');
}


}
