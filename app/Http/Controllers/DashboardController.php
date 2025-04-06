<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    switch ($user->role) {
        case 'admin':
            return view('Admin.dashboard', compact('user'));
        case 'pharmacy':
            return view('Pharmacy.dashboard', compact('user'));
        case 'customer':
            return view('Customer.dashboard', compact('user'));
        default:
            abort(403, 'Unauthorized');
    }
}

}
