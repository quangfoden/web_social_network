<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function checkUser(array $requests): array
    {
        $user = $this->userRepo->getUserByEmail($requests['email']);
        if (is_null($user)) {
            return [
                'success' => false,
                "message" => 'Tài khoản không tồn tại.'
            ];
        } else {
            $userRoles = $user->roles->pluck('name');
            if ($user->status === 0 || $user->is_lock === 1) {
                return [
                    'success' => false,
                    "message" => 'Tài khoản của bạn đã bị khoá.'
                ];
            }

            if (!$user->email_verified_at) {
                $verificationToken = Str::random(60);
                $user->update(['verification_token' => $verificationToken]);

                Mail::to($user->email)->send(new VerifyEmail($user, $verificationToken));
                return [
                    'success' => false,
                    "message" => 'Tài khoản của bạn chưa được xác thực. Vui lòng kiểm tra email của bạn để xác thực tài khoản.'
                ];
            }

            if (!Hash::check($requests['password'], $user->password)) {
                if ($userRoles->contains('admin')) {
                    return [
                        'success' => false,
                        'message' => 'Mật khẩu không chính xác.'
                    ];
                }
                $user->increment('login_attempts');
                if ($user->login_attempts >= 5) {
                    $user->status = 0;
                    $user->save();
                    return ([
                        'success' => false,
                        'message' => 'Tài khoản của bạn đã bị khóa do nhập sai mật khẩu quá 5 lần. Vui lòng liên hệ với bộ phận hỗ trợ để mở khóa tài khoản.',
                    ]);
                }
                return [
                    'success' => false,
                    'message' => 'Mật khẩu không chính xác. Bạn còn ' . (5 - $user->login_attempts) . ' lần đăng nhập.',
                ];
            } else {
                $user->login_attempts = 0;
                $user->save();
                return ['success' => true];
            }
        }
    }

    public function loginUser($credentials, $remember)
    {
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            return $user;
        }

        return null;
    }

    public function registerUser(array $requests): array
    {
        $existingUser = $this->userRepo->getUserByEmail($requests['email']);
        if (!is_null($existingUser)) {
            return [
                'success' => false,
                'message' => 'Tài khoản đã tồn tại.'
            ];
        }
        try {
            $newUser = $this->userRepo->createUser([
                'first_name' => $requests['first_name'],
                'last_name' => $requests['last_name'],
                'user_name' => $requests['user_name'],
                'avatar' => '/images/avatar.png',
                'email' => $requests['email'],
                'password' => Hash::make($requests['password']),
            ]);
            $newUser->assignRole('user');
            auth()->login($newUser);

            $verificationToken = Str::random(60);
            $newUser->update(['verification_token' => $verificationToken]);

            Mail::to($newUser->email)->send(new VerifyEmail($newUser, $verificationToken));

            return [
                'success' => true,
                'data' => $newUser
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi đăng ký.'
            ];
        }
    }
}
