<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    //
    public function allPost()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return response()->json(['posts' => $posts]);
    }

    public function createPost(Request $request)
    {
        $request->validate(['text' => 'required']);
        $post = new Post;

        if ($request->hasFile('image')) {
            $request->validate(['image' => 'required|mimes:jpg,jpeg,png']);
            $post = (new ImageService)->updateImage($post, $request);
        }

        $post->user_id = auth()->user()->id;
        $post->text = $request->input('text');
        $post->save();
    }
}
