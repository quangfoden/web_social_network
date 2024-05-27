<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    public function allCommentsByPost($postId)
    {
        return Comment::where('post_id', $postId)->get();
    }
}
