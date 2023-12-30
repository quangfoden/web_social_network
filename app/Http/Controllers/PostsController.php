<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class PostsController extends Controller
{
    //
    public function create_post(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'privacy' => 'required|in:public,friends,only_me',
            // 'media.*' => 'required|file|mimes:jpeg,png,jpg,mp4,mov,ogg,qt|max:20480',
        ]);

        $user = Auth::user();

        $post = new Post([
            'content' => $request->input('content'),
            'privacy' => $request->input('privacy'),
        ]);

        $media = new Media([]);
        $user->posts()->save($post);

        // Lưu media
        if ($request->has('media')) {
            foreach ($request->input('media') as $mediaData) {
                $path = $mediaData['url'];
                $type = $mediaData['type'];
                if ($type === 'image' || $type === 'video') {
                    $media = new Media([
                        'path' => $path,
                        'type' => $type,
                    ]);

                    $post->media()->save($media);
                }
            }
        } else {
            $mes = 'lỗi';
        }

        return response()->json(['message' => 'Bài viết của bạn đã được đăng thành công!', 'user' => $user, 'post' => $post, 'media' => $media]);
    }
}
