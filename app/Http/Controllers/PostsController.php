<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\AddPostRequest;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Services\PostService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
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
        $data = $request->validated();
        $post = $this->postRepo->create($data);
        if ($request->hasFile('files')) {
            $requestData = $request->file('files');
            foreach ($requestData as $id => $mediaData) {
                $path = 'uploads/Upload_posts/' . Str::random();
                if (!Storage::exists($path)) {
                    Storage::makeDirectory($path, 0755, true);
                }
                $name = Str::random() . '.' . $mediaData->getClientOriginalExtension();
                if (!Storage::putFileAS('public/' . $path, $mediaData, $name)) {
                    throw new \Exception("Unable to save file \"{$mediaData->getClientOriginalName()}\"");
                }
                $relativePath = $path . '/' . $name;
                Media::create([
                    'post_id' => $post->id,
                    'path' => $relativePath,
                    'url' => URL::to(Storage::url($relativePath)),
                    'type' => $mediaData->getClientMimeType(),
                ]);
            }
        }
        $postId = $post->id;
        $posts = $this->postRepo->getPostById($postId);
        $res = [
            'status' => 200,
            'success' => true,
            'message' => "Bài viết đã được thêm thành công.",
            'data_id' => $postId,
            'data' => $posts
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
    public function all_PostByUserId($userId)
    {
        // $userId = Auth::id();
        $posts = $this->postRepo->getAllByUserId($userId);
        // dd($userId);
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'success',
            'data' => $posts
        ]);
    }
    public function allposts_deleted()
    {
        $userId = Auth::id();
        $posts = $this->postRepo->getAllByUserIdDeleted($userId);
        // dd($userId);
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'success',
            'data' => $posts
        ]);
    }
    public function updatePost(AddPostRequest $request, $postId)
    {
        $ispost = Post::find($postId);
        if (!$ispost) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        $data = $request->all();
        $post = $this->postRepo->update($postId, $data);
        $deletedImages = $data['deletedImages'] ?? [];
        if (count($deletedImages) > 0) {
            $medias = Media::query()
                ->where('post_id', $postId)
                ->whereIn('id', $deletedImages)
                ->get();
            foreach ($medias as $media) {
                if ($media->path) {
                    Storage::deleteDirectory('/public/' . dirname($media->path));
                }
                $media->delete();
            }
        }
        if ($request->hasFile('files')) {
            $requestData = $request->file('files');
            foreach ($requestData as $id => $mediaData) {
                $path = 'uploads/Upload_posts/' . Str::random();
                if (!Storage::exists($path)) {
                    Storage::makeDirectory($path, 0755, true);
                }
                $name = Str::random() . '.' . $mediaData->getClientOriginalExtension();
                if (!Storage::putFileAS('public/' . $path, $mediaData, $name)) {
                    throw new \Exception("Unable to save file \"{$mediaData->getClientOriginalName()}\"");
                }
                $relativePath = $path . '/' . $name;
                Media::create([
                    'post_id' => $postId,
                    'path' => $relativePath,
                    'url' => URL::to(Storage::url($relativePath)),
                    'type' => $mediaData->getClientMimeType(),
                ]);
            }
        }
        $post = $this->postRepo->getPostById($postId);
        $res = [
            'status' => 200,
            'success' => true,
            'message' => "Bài viết đã được cập nhật thành công.",
            'post_id' => $postId,
            'data' => $post
        ];
        return response()->json(['data' => $res]);
    }

    public function trash(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->status = !$post->status;;
        $post->save();

        return response()->json([
            'message' => $post->status ? 'Bài viết đã được khôi phục thành công.' : 'Bài viết đã được chuyển vào thùng rác.'
        ]);    }
    public function pin(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->pinned = !$post->pinned; // Đảo ngược trạng thái ghim bài viết
        $post->save();

        return response()->json([
            'message' => $post->pinned ? 'Bài viết đã được ghim lên đầu.' : 'Bài viết đã được bỏ ghim.'
        ]);
    }
}
