<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

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
            if ($user->status === 0 || $user->is_lock === 1) {
                return [
                    'success' => false,
                    "message" => 'Tài khoản của bạn đã bị khoá.'
                ];
            } else {
                if (!Hash::check($requests['password'], $user->password)) {
                    return [
                        'success' => false,
                        'message' => 'Mật khẩu không đúng.'
                    ];
                } else {
                    // Đăng nhập người dùng
                    auth()->login($user);
                    return ['success' => true];
                }
            }
        }
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
                // 'email' => $requests['email'],
                // 'password' => Hash::make($requests['password']),
                // // Thêm các thông tin khác của người dùng nếu cần
                'first_name' => $requests['first_name'],
                'last_name' => $requests['last_name'],
                'user_name' => $requests['user_name'],
                'avatar' => '/images/avatar.gif',
                'email' => $requests['email'],
                'password' => Hash::make($requests['password']),
            ]);
            $newUser->assignRole('user');
            // Đăng nhập người dùng mới
            auth()->login($newUser);

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
