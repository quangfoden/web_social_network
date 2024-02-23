<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Replycomment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PostService;

class CommentController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    public function getAllComments($postId)
    {
        $comments = Comment::where('status', 1)
            ->where('post_id', $postId)
            ->with('user', 'post','repcomments')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comments) {
                $comments->created_at_formatted = $this->postService->formatTimeAgo($comments->created_at);
                return  $comments;
            });
        return response()->json(['data' => $comments]);
    }
    //
    public function create_comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            $res = [
                'message' => 'Nội dung bình luận trống !',
                'success' => false,
            ];
            return response()->json(['data' => $res]);
        }
        $user = Auth::user();
        $userId = $user->id;
        $comment = new Comment([
            // 'post_id' => $postId,
            'post_id' => $request->postId,
            'user_id' => $userId,
            'content' => $request->input('content')
        ]);
        $comment->save();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $path = 'uploads/Upload_comments/' . $filename;
            $file->storeAs('uploads/Upload_comments', $filename);
            $comment->update([
                'type' => $file->getMimeType(),
                'path' => $path,
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
            // 'post_id' => $postId,
            'post_id' =>  $comment->post_id,
            'user_id' => $comment->user_id,
            'content' => $comment->content,
            'path' => $comment->path,
            'type' => $comment->type,
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
            $filename = $file->getClientOriginalName();
            $path = 'uploads/Upload_comments/rep_comment/' . $filename;
            $file->storeAs('uploads/Upload_comments/rep_comment', $filename);
            $replycomment->update([
                'type' => $file->getMimeType(),
                'path' => $path,
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
        $res = [
            'message' => 'Bạn đã trả lời bình luận thành công !',
            'success' => true,
            'repcomment' =>  $replycomment,
        ];
        return response()->json(['data' => $res]);
    }
}
