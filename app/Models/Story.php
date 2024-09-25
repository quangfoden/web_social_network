<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'media_url',
        'posted_at',
        'expired_at',
        'status',
        'priority',
        'privacy',
        'views',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($story) {
            $story->expired_at = Carbon::now()->addHours(24);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
