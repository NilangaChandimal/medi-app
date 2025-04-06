<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'prescription_image' => 'required|image',
            'visibility' => 'required|in:public,pharmacy_only',
            'description' => 'nullable|string'
        ]);

        $post = $request->user()->posts()->create([
            'prescription_image' => $request->file('prescription_image')->store('prescriptions'),
            'visibility' => $data['visibility'],
            'description' => $data['description'],
            'status' => 'open'
        ]);

        // Dispatch event for pharmacy notifications
        event(new NewPrescriptionPosted($post));

        return response()->json($post, 201);
    }

    public function index(Request $request)
    {
        $query = Post::query()->with('customer');

        if ($request->user()->isPharmacy()) {
            $query->where('visibility', 'public')
                  ->orWhere('visibility', 'pharmacy_only');
        }

        return response()->json($query->paginate(10));
    }
}
