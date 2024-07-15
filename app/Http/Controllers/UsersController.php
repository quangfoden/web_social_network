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
    public function getUserByUser_Id($user_id)
    {
        $user = $this->userRepo->getUserByUserId($user_id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        return response()->json($user);
    }
    public function getUserById($id)
    {
        $user = $this->userRepo->getUserById($id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        return response()->json($user);
    }
    public function allaccount()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user')->where('status', 1);
        })
            ->get()
            ->toArray();

        $responseData = [
            'status' => 200,
            'success' => true,
            'message' => 'success',
            'data' => ['users' => $users]
        ];

        return response()->json($responseData);
    }

    public function getProfile(Request $request)
    {
        return response()->json([
            'myInfo' => $request->user(),
        ]);
    }
    public function getFriendChat($id)
    {
        $user = $this->userRepo->getUserChatById($id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        return response()->json($user);
    }
}
