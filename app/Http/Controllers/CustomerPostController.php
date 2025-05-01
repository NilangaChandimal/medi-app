<?php

namespace App\Http\Controllers;

use App\Models\CustomerPost;
use App\Models\Notification;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CustomerPostController extends Controller
{
    public function index()
    {
        $posts = CustomerPost::where('customer_id', Auth::id())->paginate(10);
        return view('Customer.post.index', compact('posts'));
    }


    public function create()
    {
        return view('Customer.post.create');
    }

    public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'content' => 'nullable|string',
        'image.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10048',
        'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:10000',
        'visibility' => 'required|in:pharmacy,public',
    ]);

    $posts = new CustomerPost();
    $posts->customer_id = Auth::id();
    $posts->content = $request->content;
    $posts->visibility = $request->visibility;

    $imagePaths = [];

    if ($request->hasFile('image')) {
        foreach ($request->file('image') as $image) {
            if ($image->isValid()) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('CustomerPost/images'), $imageName);
                $imagePaths[] = 'CustomerPost/images/' . $imageName;
            } else {
                return back()->withErrors(['image' => 'One or more images failed to upload. Please try again.']);
            }
        }
        $posts->image = json_encode($imagePaths);
    }

    if ($request->hasFile('video')) {
        $video = $request->file('video');
        if ($video->isValid()) {
            $videoName = time() . '_video.' . $video->getClientOriginalExtension();
            $video->move(public_path('CustomerPost/videos'), $videoName);
            $posts->video = 'CustomerPost/videos/' . $videoName;
        } else {
            return back()->withErrors(['video' => 'The video file failed to upload.']);
        }
    }

    $posts->save();

    // Optional: Send notifications, etc.
    Notification::create([
        'customer_post_id' => $posts->id,
        'customer_id' => $posts->customer_id,
        'message' => 'A new customer post is available.',
    ]);

    return redirect()->route('customer.post.index')->with('success', 'Post created successfully.');
}



    public function show($id)
    {
        $posts = CustomerPost::findOrFail($id);
        return view('Customer.post.show', compact('posts'));
    }

    public function edit($id)
    {
        $posts = CustomerPost::findOrFail($id);
        return view('Customer.post.edit', compact('posts'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'content' => 'nullable|string',
        'image.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10048',
        'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:10000',
        'visibility' => 'required|in:pharmacy,public',
    ]);

    $posts = CustomerPost::findOrFail($id);
    $posts->content = $request->content;
    $posts->visibility = $request->visibility;

    $existingImages = $posts->image ? json_decode($posts->image, true) : [];

    $removedImages = $request->input('removed_images', []);

    foreach ($removedImages as $removePath) {
        $fullPath = public_path($removePath);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
        $existingImages = array_filter($existingImages, function ($img) use ($removePath) {
            return $img !== $removePath;
        });
    }

    // Handle new uploaded images
    if ($request->hasFile('image')) {
        foreach ($request->file('image') as $image) {
            if ($image->isValid()) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('CustomerPost/images'), $imageName);
                $existingImages[] = 'CustomerPost/images/' . $imageName;
            }
        }
    }

    $posts->image = json_encode(array_values($existingImages)); // Reindex array

    // Handle video
    if ($request->hasFile('video')) {
        if ($posts->video && File::exists(public_path($posts->video))) {
            File::delete(public_path($posts->video));
        }

        $video = $request->file('video');
        $videoName = time() . '_video.' . $video->getClientOriginalExtension();
        $video->move(public_path('CustomerPost/videos'), $videoName);
        $posts->video = 'CustomerPost/videos/' . $videoName;
    }

    $posts->save();

    return redirect()->route('customer.post.index')->with('success', 'Post updated successfully.');
}

    public function destroy($id)
    {
        $posts = CustomerPost::findOrFail($id);

        // Delete files if exist
        if ($posts->image) {
            Storage::delete($posts->image);
        }
        if ($posts->video) {
            Storage::delete($posts->video);
        }

        $posts->delete();

        return redirect()->route('customer.post.index')->with('success', 'Post deleted successfully.');
    }
}
