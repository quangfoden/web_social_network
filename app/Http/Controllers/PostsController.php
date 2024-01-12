<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\PostService;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use GrahamCampbell\ResultType\Success;

class PostsController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function create_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'privacy' => 'required|in:public,friends,only_me',
        ]);

        if ($validator->fails()) {
            foreach (array_values($validator->errors()->toArray()) as $val) {
                foreach ($val as $error) {
                    $msg[] = $error;
                }
            }
            $res = [
                'message' => $msg,
                'success' => false,
            ];
            return response()->json(['data' => $res]);
        }

        $user = Auth::user();

        $post = new Post([
            'content' => $request->input('content'),
            'privacy' => $request->input('privacy'),
        ]);
        $user->posts()->save($post);
        if ($request->has('media')) {
            $requestData = $request->all();
            foreach ($requestData['media'] as $mediaData) {
                $file = $mediaData['file'];
                $type = $mediaData['type'];
                $response = $this->postService->updatemedia($post, $type, $file);
            }
            if ($response->getStatusCode() !== 200) {
                return $response;
            }
            $res = [
                'message' => 'UpLoad file thành công',
                'success' => true,
            ];
        } else {
            $res = [
                'message' => 'UpLoad file Không thành công',
                'success' => false,
            ];
        }
        $res = [
            'message' => 'Bạn đã đăng bài thành công',
            'success' => true,
            'posts' => $post
        ];
        return response()->json(['data' => $res]);
    }
    public function all_post()
    {
        $posts = Post::where('status', 1)
            ->with('user', 'media', 'comments.user', 'comments.repcomments.user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($post) {
                $post->created_at_formatted = $this->postService->formatTimeAgo($post->created_at);
                $post->comments->each(function ($comment) {
                    $comment->created_at_formatted = $this->postService->formatTimeAgo($comment->created_at);
                    if ($comment->repcomments) {
                        foreach ($comment->repcomments as $repcomment) {
                            $repcomment->created_at_formatted = $this->postService->formatTimeAgo($repcomment->created_at);
                        }
                    }
                });
                return $post;
            })
            ->toArray();
        return response()->json(['posts' => $posts]);
    }
}
