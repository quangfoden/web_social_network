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
use App\Repositories\CommentRepository;

class CommentController extends Controller
{
    protected $postService;
    protected $comments;
    public function __construct(PostService $postService, CommentRepository $comments)
    {
        $this->postService = $postService;
        $this->comments = $comments;
    }
    // public function getAllComments($postId)
    // {
    //     $comments = Comment::where('status', 1)
    //         ->where('post_id', $postId)
    //         ->with('user', 'post','repcomments')
    //         ->orderBy('created_at', 'desc')
    //         ->get()
    //         ->map(function ($comments) {
    //             $comments->created_at_formatted = $this->postService->formatTimeAgo($comments->created_at);
    //             return  $comments;
    //         });
    //     return response()->json(['data' => $comments]);
    // }
    //
    public function getAllCommentsByPostId($postId)
    {
        $comments = $this->comments->allCommentsByPost($postId);
        return response()->json($comments);
    }
    //
    public function create_comment(AddCommentRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $userId = $user->id;
        $comment = new Comment([
            'post_id' => $request->postId,
            'user_id' => $userId,
            'content' => $data['content']
        ]);
        $comment->save();
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
            $comment->update([
                'type' => $file->getClientMimeType(),
                'path' => $relativePath,
                'url' => URL::to(Storage::url($relativePath)),
            ]);
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

        $formattedComments = [
            'id' => $comment->id,
            'post_id' =>  $comment->post_id,
            'user_id' => $comment->user_id,
            'content' => $comment->content,
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

    public function updateComment(UpdateCommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $data = $request->validated();
        $comment->update([
            'content' => $data['content']
        ]);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = 'uploads/Upload_comments/' . Str::random();
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path, 0755, true);
            }
            $name = Str::random() . '.' . $file->getClientOriginalExtension();
            if (!Storage::putFileAs('public/' . $path, $file, $name)) {
                throw new \Exception("Unable to save file \"{$file->getClientOriginalName()}\"");
            }
            $relativePath = $path . '/' . $name;
            $comment->update([
                'type' => $file->getClientMimeType(),
                'path' => $relativePath,
                'url' => URL::to(Storage::url($relativePath)),
            ]);
        }

        return response()->json([
            'message' => 'Bình luận đã được cập nhật thành công!',
            'success' => true,
            'comment' => $comment
        ]);
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
        $replycomment = new Replycomment([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'content' => $request->input('content')
        ]);
        $replycomment->save();
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
            $replycomment->update([
                'type' => $file->getClientMimeType(),
                'path' => $relativePath,
                'url' => URL::to(Storage::url($relativePath)),
            ]);
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
}
