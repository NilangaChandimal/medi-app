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
        // Fetch posts only for the currently authenticated customer
        $posts = CustomerPost::where('customer_id', Auth::id())->paginate(10);
        //$posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('Customer.post.index', compact('posts'));
    }


    public function create()
    {
        return view('Customer.post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:10000',
            'visibility' => 'required|in:pharmacy,public',
        ]);

        $posts = new CustomerPost();
        $posts->customer_id = Auth::id();
        $posts->content = $request->content;
        $posts->visibility = $request->visibility;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();
            $image->move(public_path('CustomerPost/images'), $imageName);
            $posts->image = 'CustomerPost/images/' . $imageName;
        }

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_video.' . $video->getClientOriginalExtension();
            $video->move(public_path('CustomerPost/videos'), $videoName);
            $posts->video = 'CustomerPost/videos/' . $videoName;
        }

        $posts->save();

        // if ($posts->visibility === 'pharmacy') {
            Pharmacy::where('is_blocked', false)->where('status', 'active')->get();


                Notification::create([
                    'customer_post_id' => $posts->id,
                    'customer_id' => $posts->customer_id,
                    'message' => 'A new customer post is available.',
                ]);
            
        // }

        return redirect()->route('customer.post.index')->with('success', 'Posts created successfully.');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:10000',
            'visibility' => 'required|in:pharmacy,public,',
        ]);

        $posts = CustomerPost::findOrFail($id);
        $posts->content = $request->content;
        $posts->visibility = $request->visibility;

        // Update image if a new file is uploaded
        if ($request->hasFile('image')) {
            if ($posts->image && File::exists(public_path($posts->image))) {
                File::delete(public_path($posts->image)); // Delete old image from public
            }

            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();
            $image->move(public_path('CustomerPost/images'), $imageName);
            $posts->image = 'CustomerPost/images/' . $imageName;
        }

        if ($request->hasFile('video')) {
            if ($posts->video && File::exists(public_path($posts->video))) {
                File::delete(public_path($posts->video)); // Delete old video from public
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

        // Delete files if they exist
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
