<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class FaceId extends Model
{
    use HasFactory;

    protected $fillable = [
       'user_id',
       'face_image'
    ];
    // Định nghĩa mối quan hệ n-1 với bảng users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
