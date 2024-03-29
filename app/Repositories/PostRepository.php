<?php

namespace App\Repositories;

use App\Services\PostService;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    protected Post $model;
    protected PostService $postService;
    public function __construct(Post $post, PostService $postService)
    {
        $this->model = $post;
        $this->postService = $postService;
    }

    public function getAll()
    {
        $query = $this->model->query()->with('user', 'media', 'comments.user', 'comments.repcomments.user')
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('privacy', 'public')
                    ->orWhere('privacy', 'friends');
            })
            ->orderBy('created_at', 'desc');
        $posts = $query->paginate(5);
        $posts->getCollection()->transform(function ($post) {
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
        });
        return $posts;
    }
    public function getPostById($postId): array
    {
        // Lấy bài viết từ cơ sở dữ liệu với ID tương ứng
        $post = $this->model->query()
            ->with('user', 'media', 'comments.user', 'comments.repcomments.user')
            ->where('id', $postId)
            ->first();

        // Kiểm tra xem bài viết có tồn tại không
        if ($post) {
            // Định dạng thời gian cho bài viết và các bình luận
            $post->created_at_formatted = $this->postService->formatTimeAgo($post->created_at);
            $post->comments->each(function ($comment) {
                $comment->created_at_formatted = $this->postService->formatTimeAgo($comment->created_at);
                if ($comment->repcomments) {
                    foreach ($comment->repcomments as $repcomment) {
                        $repcomment->created_at_formatted = $this->postService->formatTimeAgo($repcomment->created_at);
                    }
                }
            });

            // Trả về bài viết dưới dạng mảng
            return $post->toArray();
        }

        // Trường hợp không tìm thấy bài viết, trả về null
        return null;
    }
    public function getAllByUserId(int $userId): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->where('status', 1)
            ->where('user_id', $userId) // Lọc bài viết theo ID của người dùng
            ->with('user', 'media', 'comments.user', 'comments.repcomments.user')
            ->orderByRaw('CASE WHEN pinned = 1 THEN 0 ELSE 1 END')
            ->orderBy('created_at', 'desc');

        $posts = $query->paginate(10);

        $posts->getCollection()->transform(function ($post) {
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
        });

        return $posts;
    }
    public function getAllByUserIdDeleted(int $userId): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->where('status', 0)
            ->where('user_id', $userId)
            ->with('user', 'media', 'comments.user', 'comments.repcomments.user')
            ->orderByRaw('CASE WHEN pinned = 1 THEN 0 ELSE 1 END')
            ->orderBy('created_at', 'desc');

        $posts = $query->paginate(10);

        $posts->getCollection()->transform(function ($post) {
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
        });

        return $posts;
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $data['user_id'] = auth()->user()->id ?? 1;
        return $this->model->query()->create($data);
    }
    public function update(int $id, array $data): bool|int
    {
        return $this->model->query()->find($id)->update($data);
    }
}
