<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Events\SetDefaultUserRole;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'user_name',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'cover_photo',
        'birthdate',
        'gender',
        'phone_number',
        'address',
        'bio',
        'status',
        'is_lock',
        'first_login',
        'remember_token',
        'login_attempts'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function generateUniqueUserId()
    {
        do {
            $user_id = rand(1000000000, 9999999999);
        } while (self::where('user_id', $user_id)->exists());

        return $user_id;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = self::generateUniqueUserId();
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function faceIds()
    {
        return $this->hasMany(FaceId::class);
    }

    public function userFaceRegs()
    {
        return $this->hasMany(UserFaceReg::class);
    }
}
