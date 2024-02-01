<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\AddPostRequest;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\PostService;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use GrahamCampbell\ResultType\Success;

class PostsController extends Controller
{
    protected $postService;
    protected PostRepository $postRepo;
    public function __construct(PostService $postService, PostRepository $postRepo)
    {
        $this->postRepo = $postRepo;
        $this->postService = $postService;
    }

    public function create_post(AddPostRequest $request)
    {
        $data = $request->all();
        $post = $this->postRepo->create($data);
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
                'message' => 'Không tìm thấy file uploads',
                'success' => false,
            ];
        }
        $res = [
            'status' => 200,
            'success' => true,
            'message' => "Bài viết đã được thêm thành công.",
            'data_id' => $post->id
        ];
        return response()->json(['data' => $res]);
    }
    public function all_post()
    {
        $posts = $this->postRepo->getAll();

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'success',
            'data' => $posts
        ]);
    }
}
