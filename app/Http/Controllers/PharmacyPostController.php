<?php

namespace App\Http\Controllers;

use App\Models\PharmacyPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PharmacyPostController extends Controller
{
    public function index()
    {
        // Fetch posts only for the currently authenticated pharmacy
        $posts = PharmacyPost::where('pharmacy_id', Auth::id())->paginate(10);
        //$posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('Pharmacy.post.index', compact('posts'));
    }


    public function create()
    {
        return view('Pharmacy.post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:10000',
        ]);

        $posts = new PharmacyPost();
        $posts->pharmacy_id = Auth::id();
        $posts->content = $request->content;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();
            $image->move(public_path('PharmacyPost/images'), $imageName);
            $posts->image = 'PharmacyPost/images/' . $imageName;
        }

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_video.' . $video->getClientOriginalExtension();
            $video->move(public_path('PharmacyPost/videos'), $videoName);
            $posts->video = 'PharmacyPost/videos/' . $videoName;
        }


        $posts->save();

        return redirect()->route('pharmacy.post.index')->with('success', 'Posts created successfully.');
    }

    public function show($id)
    {
        $posts = PharmacyPost::findOrFail($id);
        return view('Pharmacy.post.show', compact('posts'));
    }

    public function edit($id)
    {
        $posts = PharmacyPost::findOrFail($id);
        return view('Pharmacy.post.edit', compact('posts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:10000',
        ]);

        $posts = PharmacyPost::findOrFail($id);
        $posts->content = $request->content;

        // Update image if a new file is uploaded
        if ($request->hasFile('image')) {
            if ($posts->image && File::exists(public_path($posts->image))) {
                File::delete(public_path($posts->image)); // Delete old image from public
            }

            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();
            $image->move(public_path('PharmacyPost/images'), $imageName);
            $posts->image = 'PharmacyPost/images/' . $imageName;
        }

        if ($request->hasFile('video')) {
            if ($posts->video && File::exists(public_path($posts->video))) {
                File::delete(public_path($posts->video)); // Delete old video from public
            }

            $video = $request->file('video');
            $videoName = time() . '_video.' . $video->getClientOriginalExtension();
            $video->move(public_path('PharmacyPost/videos'), $videoName);
            $posts->video = 'PharmacyPost/videos/' . $videoName;
        }

        $posts->save();

        return redirect()->route('pharmacy.post.index')->with('success', 'Post updated successfully.');
    }


    public function destroy($id)
    {
        $posts = PharmacyPost::findOrFail($id);

        // Delete files if they exist
        if ($posts->image) {
            Storage::delete($posts->image);
        }
        if ($posts->video) {
            Storage::delete($posts->video);
        }

        $posts->delete();

        return redirect()->route('pharmacy.post.index')->with('success', 'Post deleted successfully.');
    }
}
