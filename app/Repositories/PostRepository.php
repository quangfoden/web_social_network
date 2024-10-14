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

    // public function getAll()
    // {
    //     $query = $this->model->query()->with('user', 'likes', 'likes.user', 'media', 'comments.user', 'comments.repcomments.user')
    //         ->where('status', 1)
    //         ->where(function ($query) {
    //             $query->where('privacy', 'public')
    //                 ->orWhere('privacy', 'friends');
    //         })
    //         ->orderBy('created_at', 'desc');
    //     $posts = $query->paginate(5);
    //     $posts->getCollection()->transform(function ($post) {
    //         $post->created_at_formatted = $this->postService->formatTimeAgo($post->created_at);
    //         $post->comment_count =  $post->comments->count();
    //         $post->like_count =  $post->likes->count();
    //         $post->comments->each(function ($comment) {
    //             $comment->repcomment_count = $comment->repcomments->count();
    //             $comment->created_at_formatted = $this->postService->formatTimeAgo($comment->created_at);
    //             if ($comment->repcomments) {
    //                 foreach ($comment->repcomments as $repcomment) {
    //                     $repcomment->created_at_formatted = $this->postService->formatTimeAgo($repcomment->created_at);
    //                 }
    //             }
    //         });
    //         return $post;
    //     });
    //     return $posts;
    // }
    // public function getAll()
    // {
    //     $currentUser = auth()->user(); // Lấy người dùng hiện tại
    
    //     // Query để lấy bài post
    //     $query = $this->model->query()
    //         ->with('user', 'likes', 'likes.user', 'media', 'comments.user', 'comments.repcomments.user')
    //         ->where('status', 1) // Bài viết phải ở trạng thái được phê duyệt
    //         ->where(function ($query) use ($currentUser) {
    //             $query->where('privacy', 'public') // Bài viết công khai
    //                 ->orWhere(function ($query) use ($currentUser) {
    //                     // Bài viết bạn bè, chỉ hiển thị nếu là bạn hoặc người dùng đăng bài
    //                     $query->where('privacy', 'friends')
    //                         ->where(function ($q) use ($currentUser) {
    //                             // Cho phép xem nếu là người đăng bài
    //                             $q->where('user_id', $currentUser->id) 
    //                                 // Hoặc người dùng hiện tại là bạn bè của người đăng bài
    //                                 ->orWhereHas('user', function ($q) use ($currentUser) {
    //                                     $q->whereHas('friends', function ($q) use ($currentUser) {
    //                                         $q->where(function ($q) use ($currentUser) {
    //                                             $q->where('friends.user_id', $currentUser->id)
    //                                                 ->orWhere('friends.friend_id', $currentUser->id);
    //                                         })
    //                                         ->where('friends.status', 'accepted'); // Chỉ lấy bạn bè có status là 'accepted'
    //                                     });
    //                                 });
    //                         });
    //                 });
    //         })
    //         ->orderBy('created_at', 'desc'); // Sắp xếp theo ngày tạo mới nhất
    
    //     // Paginate kết quả
    //     $posts = $query->paginate(5);
    
    //     // Format lại dữ liệu cho post
    //     $posts->getCollection()->transform(function ($post) {
    //         $post->created_at_formatted = $this->postService->formatTimeAgo($post->created_at);
    //         $post->comment_count = $post->comments->count();
    //         $post->like_count = $post->likes->count();
    
    //         // Format dữ liệu cho comments và repcomments
    //         $post->comments->each(function ($comment) {
    //             $comment->repcomment_count = $comment->repcomments->count();
    //             $comment->created_at_formatted = $this->postService->formatTimeAgo($comment->created_at);
    //             $comment->repcomments->each(function ($repcomment) {
    //                 $repcomment->created_at_formatted = $this->postService->formatTimeAgo($repcomment->created_at);
    //             });
    //         });
    
    //         return $post;
    //     });
    
    //     return $posts;
    // }
    public function getAll()
{
    $currentUser = auth()->user(); // Lấy người dùng hiện tại

    // Query để lấy bài post
    $query = $this->model->query()
        ->with('user','shares','shares.user', 'likes', 'likes.user', 'media', 'comments.user', 'comments.repcomments.user', 'shares') // Thêm 'shares' để lấy các bài post được chia sẻ
        ->where('status', 1) // Bài viết phải ở trạng thái được phê duyệt
        ->where(function ($query) use ($currentUser) {
            $query->where('privacy', 'public') // Bài viết công khai
                ->orWhere(function ($query) use ($currentUser) {
                    // Bài viết bạn bè, chỉ hiển thị nếu là bạn hoặc người dùng đăng bài
                    $query->where('privacy', 'friends')
                        ->where(function ($q) use ($currentUser) {
                            // Cho phép xem nếu là người đăng bài
                            $q->where('user_id', $currentUser->id) 
                                // Hoặc người dùng hiện tại là bạn bè của người đăng bài
                                ->orWhereHas('user', function ($q) use ($currentUser) {
                                    $q->whereHas('friends', function ($q) use ($currentUser) {
                                        $q->where(function ($q) use ($currentUser) {
                                            $q->where('friends.user_id', $currentUser->id)
                                                ->orWhere('friends.friend_id', $currentUser->id);
                                        })
                                        ->where('friends.status', 'accepted'); // Chỉ lấy bạn bè có status là 'accepted'
                                    });
                                });
                        });
                });
        })
        ->orWhereHas('shares', function ($q) use ($currentUser) {
            $q->where(function ($q) use ($currentUser) {
                $q->where('privacy', 'public')
                  ->orWhere(function ($q) use ($currentUser) {
                      $q->where('privacy', 'friends')
                          ->where('user_id', '!=', $currentUser->id) // Chỉ lấy bài viết chia sẻ không phải của người dùng hiện tại
                          ->whereHas('user', function ($q) use ($currentUser) {
                              $q->whereHas('friends', function ($q) use ($currentUser) {
                                  $q->where(function ($q) use ($currentUser) {
                                      $q->where('friends.user_id', $currentUser->id)
                                          ->orWhere('friends.friend_id', $currentUser->id);
                                  })
                                  ->where('friends.status', 'accepted'); // Chỉ lấy bạn bè có status là 'accepted'
                              });
                          });
                  });
            });
        })
        ->orderBy('created_at', 'desc'); // Sắp xếp theo ngày tạo mới nhất

    // Paginate kết quả
    $posts = $query->paginate(5);

    // Format lại dữ liệu cho post
    $posts->getCollection()->transform(function ($post) {
        $post->created_at_formatted = $this->postService->formatTimeAgo($post->created_at);
        $post->comment_count = $post->comments->count();
        $post->like_count = $post->likes->count();

        // Format dữ liệu cho comments và repcomments
        $post->comments->each(function ($comment) {
            $comment->repcomment_count = $comment->repcomments->count();
            $comment->created_at_formatted = $this->postService->formatTimeAgo($comment->created_at);
            $comment->repcomments->each(function ($repcomment) {
                $repcomment->created_at_formatted = $this->postService->formatTimeAgo($repcomment->created_at);
            });
        });

        return $post;
    });

    return $posts;
}

    public function getPostById($postId): array
    {
        $post = $this->model->query()
            ->with('user','shares','shares.user', 'media','comments.user', 'likes', 'likes.user', 'comments.repcomments.user')
            ->where('id', $postId)
            ->first();

        if ($post) {
            $post->created_at_formatted = $this->postService->formatTimeAgo($post->created_at);
            $post->comment_count =  $post->comments->count();
            $post->like_count =  $post->likes->count();
            $post->comments->each(function ($comment) {
                $comment->repcomment_count = $comment->repcomments->count();
                $comment->created_at_formatted = $this->postService->formatTimeAgo($comment->created_at);
                if ($comment->repcomments) {
                    foreach ($comment->repcomments as $repcomment) {
                        $repcomment->created_at_formatted = $this->postService->formatTimeAgo($repcomment->created_at);
                    }
                }
            });
            return $post->toArray();
        }
        return null;
    }
    public function getAllByUserId(int $userId): LengthAwarePaginator
    {
        $currentUser = auth()->user(); 
    
        $query = $this->model->query()
            ->where('status', 1)
            ->where(function ($query) use ($userId) {
                // Lấy bài viết của người dùng cụ thể hoặc bài chia sẻ của chính người dùng đó
                $query->where('user_id', $userId) // Bài viết của Uyên Lê
                      ->orWhereHas('shares', function ($query) use ($userId) {
                          $query->where('user_id', $userId); // Bài chia sẻ của Uyên Lê
                      });
            })
            ->with('user', 'media', 'shares', 'shares.user', 'comments.user', 'likes', 'likes.user', 'comments.repcomments.user')
            ->orderByRaw('CASE WHEN pinned = 1 THEN 0 ELSE 1 END')
            ->where(function ($query) use ($currentUser) {
                $query->where('privacy', 'public') // Bài viết công khai
                    ->orWhere(function ($query) use ($currentUser) {
                        // Bài viết bạn bè, chỉ hiển thị nếu là bạn hoặc người dùng đăng bài
                        $query->where('privacy', 'friends')
                            ->where(function ($q) use ($currentUser) {
                                // Cho phép xem nếu là người đăng bài
                                $q->where('user_id', $currentUser->id) 
                                    // Hoặc người dùng hiện tại là bạn bè của người đăng bài
                                    ->orWhereHas('user', function ($q) use ($currentUser) {
                                        $q->whereHas('friends', function ($q) use ($currentUser) {
                                            $q->where(function ($q) use ($currentUser) {
                                                $q->where('friends.user_id', $currentUser->id)
                                                    ->orWhere('friends.friend_id', $currentUser->id);
                                            })
                                            ->where('friends.status', 'accepted'); // Chỉ lấy bạn bè có status là 'accepted'
                                        });
                                    });
                            });
                    });
            })
            ->orderBy('created_at', 'desc');
    
        $posts = $query->paginate(10);
        $posts->getCollection()->transform(function ($post) {
            $post->created_at_formatted = $this->postService->formatTimeAgo($post->created_at);
            $post->comment_count = $post->comments->count();
            $post->like_count =  $post->likes->count();
    
            // Format comments
            $post->comments->each(function ($comment) {
                $comment->repcomment_count = $comment->repcomments->count();
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
    
    
    public function getAllPostAboutProfile(int $userId)
    {
        $posts = $this->model->query()
            ->where('status', 1)
            ->where('user_id', $userId)
            ->with('user', 'media', 'comments.user', 'likes', 'likes.user', 'comments.repcomments.user')
            ->orderByRaw('CASE WHEN pinned = 1 THEN 0 ELSE 1 END')
            ->orderBy('created_at', 'desc')
            ->get(); 

        $posts->transform(function ($post) {
            $post->created_at_formatted = $this->postService->formatTimeAgo($post->created_at);
            $post->comment_count = $post->comments->count();
            $post->like_count =  $post->likes->count();
            $post->comments->each(function ($comment) {
                $comment->repcomment_count = $comment->repcomments->count();
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
            ->with('user', 'media', 'comments.user','likes', 'likes.user',  'comments.repcomments.user')
            ->orderByRaw('CASE WHEN pinned = 1 THEN 0 ELSE 1 END')
            ->orderBy('created_at', 'desc');

        $posts = $query->paginate(10);

        $posts->getCollection()->transform(function ($post) {
            $post->created_at_formatted = $this->postService->formatTimeAgo($post->created_at);
            $post->comment_count = $post->comments->count();
            $post->like_count =  $post->likes->count();
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
