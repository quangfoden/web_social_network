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
            ->with('user', 'post')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comments) {
                $comments->created_at_formatted = $this->postService->formatTimeAgo($comments->created_at);
                return  $comments;
            });
        return response()->json(['data' => $comments]);
    }
    //
    public function create_comment(Request $request, $postId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
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
        $userId = $user->id;
        $comment = new Comment([
            'post_id' => $postId,
            'user_id' => $userId,
            'content' => $request->input('content')
        ]);
        $comment->save();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // $path = $file->store('public/uploads/Upload_comments'); 
            $filename = $file->getClientOriginalName();
            $path = 'uploads/Upload_comments/' . $filename;
            $file->storeAs('uploads/Upload_comments', $filename);
            // Cập nhật thông tin về ảnh trong comment
            $comment->update([
                'type' => $file->getMimeType(),
                'path' => $path,
            ]);
            $res = 'success';
        } else {
            $res = 'lỗi';
        }
        return response()->json(['data' => $comment, $res]);
    }
    public function create_rep_comment(Request $request, $commentId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
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
        $userId = $user->id;
        $replycomment = new Replycomment([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'content' => $request->input('content')
        ]);
        $replycomment->save();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // $path = $file->store('public/uploads/Upload_comments'); 
            $filename = $file->getClientOriginalName();
            $path = 'uploads/Upload_comments/rep_comment/' . $filename;
            $file->storeAs('uploads/Upload_comments/rep_comment', $filename);
            // Cập nhật thông tin về ảnh trong comment
            $replycomment->update([
                'type' => $file->getMimeType(),
                'path' => $path,
            ]);
            $res = 'success';
        } else {
            $res = 'lỗi';
        }
        return response()->json(['data' => $replycomment, $res]);
    }
}
