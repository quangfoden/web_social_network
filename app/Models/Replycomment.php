<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replycomment extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment_id',
        'user_id',
        'content',
        'path',
        'type',
        'likes_count',
        'status',
    ];
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}