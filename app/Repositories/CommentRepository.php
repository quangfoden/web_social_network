<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    protected Comment $model;
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }

    public function allCommentsByPost($postId)
    {
        return Comment::where('post_id', $postId)->get();
    }

    public function update(int $id, array $data): bool|int
    {
        return $this->model->query()->find($id)->update($data);
    }
}
