<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friends;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    //
    public function index()
    {
        $loggedInUserId = Auth::id();

        $friends = Friends::where('user_id', $loggedInUserId)
            ->with('user')
            ->get();
        return response()->json($friends);
    }
    public function mutual_friends()
    {
        // Lấy ID của người dùng đã đăng nhập
        $userId = Auth::id();

        // Lấy danh sách các bạn bè của người dùng đã đăng nhập
        $userFriends = Friends::where('user_id', $userId)->pluck('friend_id');

        // Lấy danh sách các bạn bè chung giữa hai người dùng
        $mutualFriends = Friends::whereIn('user_id', $userFriends)
            ->where('friend_id', $userId)
            ->with('user')
            ->get();

        return response()->json($mutualFriends);
    }
}
