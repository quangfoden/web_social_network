<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Replycomment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PostService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Comment\AddCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\Comment\UpdateRepCommentRequest;
use App\Repositories\CommentRepository;
use App\Repositories\RepCommentRepository;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    protected $postService;
    protected $commentRepo;
    protected $repCommentRepo;
    public function __construct(PostService $postService, CommentRepository $commentRepo, RepCommentRepository $repCommentRepo)
    {
        $this->postService = $postService;
        $this->commentRepo = $commentRepo;
        $this->repCommentRepo = $repCommentRepo;
    }
    public function getAllCommentsByPostId($postId)
    {
        $comments = $this->commentRepo->allCommentsByPost($postId);
        return response()->json($comments);
    }

    public function create_comment(AddCommentRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $userId = $user->id;
        $commentData = [
            'post_id' => $request->postId,
            'user_id' => $userId,
            'content' => $data['content']
        ];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = 'uploads/Upload_comments/' . Str::random();
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path, 0755, true);
            }
            $name = Str::random() . '.' .  $file->getClientOriginalExtension();
            if (!Storage::putFileAS('public/' . $path, $file, $name)) {
                throw new \Exception("Unable to save file \"{$file->getClientOriginalName()}\"");
            }
            $relativePath = $path . '/' . $name;
            $commentData['type'] = $file->getClientMimeType();
            $commentData['path'] =  $relativePath;
            $commentData['url'] = URL::to(Storage::url($relativePath));
            $res = [
                'message' => 'upload file thành công !',
                'success' => true,
            ];
        } else {
            $res = [
                'message' => 'Không có file !',
                'success' => true,
            ];
        }
        $comment = new Comment($commentData);
        $comment->save();
        $formattedComments = [
            'id' => $comment->id,
            'post_id' =>  $comment->post_id,
            'user_id' => $comment->user_id,
            'content' => $comment->content,
            'repcomment_count' => $comment->repcomments->count(),
            'path' => $comment->path,
            'type' => $comment->type,
            'url' => $comment->url,
            'likes_count' => $comment->likes_count,
            'status' => $comment->status,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
            'created_at_formatted' => $this->postService->formatTimeAgo($comment->created_at), // Example of formatting created_at
            'user' => $comment->user,
            'repcomments' => $comment->repcomments,
        ];

        $res = [
            'message' => 'Bạn đã bình luận thành công',
            'success' => true,
            'comment' =>  $formattedComments,
        ];
        return response()->json(['data' => $res]);
    }

    public function updateComment(UpdateCommentRequest $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        $data = $request->all();
        $this->commentRepo->update($commentId, $data);

        $deletedFile = $data['deletedFile'] ?? [];
        if (count($deletedFile) > 0) {
            if ($comment->path && in_array($commentId, $deletedFile)) {
                Storage::deleteDirectory('/public/' . dirname($comment->path));
                $comment->update([
                    'url' => null,
                    'type' => null,
                    'path' => null
                ]);
            }
        }

        if ($request->hasFile('file')) {
            $requestData = $request->file('file');
            $path = 'uploads/Upload_comments/' . Str::random();
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path, 0755, true);
            }
            $name = Str::random() . '.' . $requestData->getClientOriginalExtension();
            if (!Storage::putFileAS('public/' . $path, $requestData, $name)) {
                throw new \Exception("Unable to save file \"{$requestData->getClientOriginalName()}\"");
            }
            $relativePath = $path . '/' . $name;
            $comment->update([
                'path' => $relativePath,
                'url' => URL::to(Storage::url($relativePath)),
                'type' => $request->file('file')->getClientMimeType()
            ]);
        }

        $updatedComment = Comment::findOrFail($commentId);
        return response()->json([
            'message' => 'Bình luận đã được cập nhật thành công!',
            'success' => true,
            'comment' => $updatedComment
        ]);
    }
    public function delete_comment(Request $request, $id)
    {
        try {
            // Tìm bình luận bằng ID
            $comment = Comment::findOrFail($id);

            // Kiểm tra xem bình luận có thuộc về người dùng hiện tại không
            $user = Auth::user();
            if ($comment->user_id !== $user->id) {
                return response()->json(['message' => 'Bạn không có quyền xóa bình luận này!', 'success' => false], 403);
            }

            // Nếu bình luận có file đính kèm, xóa file đó
            if ($comment->path) {
                Storage::deleteDirectory('public/' . dirname($comment->path));
            }

            // Xóa bình luận
            $comment->delete();

            return response()->json(['message' => 'Xóa bình luận thành công!', 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi khi xóa bình luận!', 'success' => false], 500);
        }
    }

    public function create_rep_comment(Request $request, $commentId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            $res = [
                'message' => 'Nội dung trả lời bình luận trống !',
                'success' => false,
            ];
            return response()->json(['data' => $res]);
        }
        $user = Auth::user();
        $userId = $user->id;
        $replyCommentData = [
            'comment_id' => $commentId,
            'user_id' => $userId,
            'content' => $request->input('content'),
        ];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = 'uploads/Upload_comments/rep_comment/' . Str::random();
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path, 0755, true);
            }
            $name = Str::random() . '.' .  $file->getClientOriginalExtension();
            if (!Storage::putFileAS('public/' . $path, $file, $name)) {
                throw new \Exception("Unable to save file \"{$file->getClientOriginalName()}\"");
            }
            $relativePath = $path . '/' . $name;
            $replyCommentData['type'] = $file->getClientMimeType();
            $replyCommentData['path'] = $relativePath;
            $replyCommentData['url'] = URL::to(Storage::url($relativePath));
            $res = [
                'message' => 'upload file thành công !',
                'success' => true,
            ];
        } else {
            $res = [
                'message' => 'không có file !',
                'success' => true,
            ];
        }
        $replycomment = new Replycomment($replyCommentData);
        $replycomment->save();
        $formattedNewRepComments = [
            'id' => $replycomment->id,
            'comment_id' =>  $commentId,
            'user_id' => $replycomment->user_id,
            'content' => $replycomment->content,
            'path' => $replycomment->path,
            'type' => $replycomment->type,
            'url' => $replycomment->url,
            'likes_count' => $replycomment->likes_count,
            'status' => $replycomment->status,
            'created_at' => $replycomment->created_at,
            'updated_at' => $replycomment->updated_at,
            'created_at_formatted' => $this->postService->formatTimeAgo($replycomment->created_at), // Example of formatting created_at
            'user' => $replycomment->user,
        ];
        $res = [
            'message' => 'Bạn đã trả lời bình luận thành công !',
            'success' => true,
            'repcomment' =>  $formattedNewRepComments,
        ];
        return response()->json(['data' => $res]);
    }
    public function updateRepComment(UpdateRepCommentRequest $request, $repCommentId)
    {
        $repcomment = Replycomment::findOrFail($repCommentId);

        $data = $request->all();
        $this->repCommentRepo->update($repCommentId, $data);

        $deletedFile = $data['deletedFile'] ?? [];
        if (count($deletedFile) > 0) {
            if ($repcomment->path && in_array($repCommentId, $deletedFile)) {
                Storage::deleteDirectory('/public/' . dirname($repcomment->path));
                $repcomment->update([
                    'url' => null,
                    'type' => null,
                    'path' => null
                ]);
            }
        }

        if ($request->hasFile('file')) {
            $requestData = $request->file('file');
            Log::info($requestData->getClientMimeType());
            $path = 'uploads/Upload_comments/rep_comment/' . Str::random();
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path, 0755, true);
            }
            $name = Str::random() . '.' . $requestData->getClientOriginalExtension();
            if (!Storage::putFileAS('public/' . $path, $requestData, $name)) {
                throw new \Exception("Unable to save file \"{$requestData->getClientOriginalName()}\"");
            }
            $relativePath = $path . '/' . $name;
            $repcomment->update([
                'path' => $relativePath,
                'url' => URL::to(Storage::url($relativePath)),
                'type' => $requestData->getClientMimeType(),
            ]);
        }

        $updatedRepComment = Replycomment::findOrFail($repCommentId);
        return response()->json([
            'message' => 'Trả lời bình luận đã được cập nhật thành công!',
            'success' => true,
            'repcomment' => $updatedRepComment
        ]);
    }
    public function delete_repcomment(Request $request, $id)
    {
        try {
            $repcomment = Replycomment::findOrFail($id);

            $user = Auth::user();
            if ($repcomment->user_id !== $user->id) {
                return response()->json(['message' => 'Bạn không có quyền xóa bình luận này!', 'success' => false], 403);
            }

            if ($repcomment->path) {
                Storage::deleteDirectory('public/' . dirname($repcomment->path));
            }

            $repcomment->delete();

            return response()->json(['message' => 'Xóa bình luận thành công!', 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi khi xóa bình luận!', 'success' => false], 500);
        }
    }
}
