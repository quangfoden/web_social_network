<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Friends extends Model
{
    use HasFactory;
    protected $table = 'friends';
    protected $fillable = [
        'user_id',
        'friend_id',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->withTimestamps();
    }
}
