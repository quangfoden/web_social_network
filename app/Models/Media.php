<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'path', 'url', 'type'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
