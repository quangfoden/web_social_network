<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserFaceReg extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_name'
     ];
    // Định nghĩa mối quan hệ n-1 với bảng users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
