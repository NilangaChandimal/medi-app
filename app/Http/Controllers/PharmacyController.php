<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PharmacyController extends Controller
{
    public function dashboard(Request $request)
    {
        $pharmacy = $request->user()->pharmacy()->withCount([
            'orders',
            'chats',
            'notifications' => function($query) {
                $query->whereNull('read_at');
            }
        ])->first();

        return response()->json([
            'stats' => $pharmacy,
            'pending_orders' => $pharmacy->orders()->where('status', 'pending')->count(),
            'active_chats' => $pharmacy->chats()->count()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'address' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'license_details' => 'sometimes|file'
        ]);

        $pharmacy = $request->user()->pharmacy;

        if ($request->hasFile('license_details')) {
            Storage::delete($pharmacy->license_details);
            $data['license_details'] = $request->file('license_details')->store('licenses');
        }

        $pharmacy->update($data);

        return response()->json($pharmacy);
    }
}
