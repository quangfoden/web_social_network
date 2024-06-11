<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UsersController extends Controller
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function getUserById($user_id)
    {
        $user = $this->userRepo->getUserById($user_id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        return response()->json($user);
    }
}
