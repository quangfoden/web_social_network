<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friends;
use Illuminate\Support\Facades\Auth;
use App\Events\FriendRequestSent;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class FriendController extends Controller
{
    public function sendRequest(Request $request)
    {
        $friendId = $request->input('friend_id');
        $userId = Auth::id();
        $user = User::find($userId);
        if ($user) {
            $userName = $user->user_name;
            $avatar = $user->avatar;
            $friendId2 = $user->user_id;
        } else {
            $userName = '';
            $avatar = '';
        }
        $friendship = Friends::where(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)
                ->where('friend_id', $friendId);
        })->orWhere(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $friendId)
                ->where('friend_id', $userId);
        })->first();
        if (!$friendship) {
            Friends::create([
                'user_id' => $userId,
                'friend_id' => $friendId,
                'status' => 'pending',
            ]);
            broadcast(new FriendRequestSent($friendId, $userId, $userName, $avatar, $friendId2))->toOthers();

            return response()->json(['message' => 'Friend request sent.']);
        } elseif ($friendship->status === 'pending') {
            return response()->json(['message' => 'Friend request is already pending.'], 409);
        } elseif ($friendship->status === 'accepted') {
            return response()->json(['message' => 'You are already friends.'], 409);
        }

        return response()->json(['message' => 'Friend request already exists.'], 409);
    }

    // Chấp nhận lời mời kết bạn
    public function acceptRequest($id)
    {
        $friendship = Friends::where('user_id', $id)
            ->where('friend_id', Auth::id())
            ->first();

        if ($friendship) {
            $friendship->status = 'accepted';
            $friendship->save();
            return response()->json(['message' => 'Friend request accepted.']);
        }

        return response()->json(['message' => 'Friend request not found.'], 404);
    }

    public function cancelRequest(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        $friendship = Friends::where(function ($query) use ($request) {
            $query->where('user_id', Auth::id())
                ->where('friend_id', $request->friend_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('user_id', $request->friend_id)
                ->where('friend_id', Auth::id());
        })->first();

        if ($friendship && $friendship->status === 'pending') {
            $friendship->delete(); // Xoá yêu cầu kết bạn nếu trạng thái là pending
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Cannot cancel this request.']);
    }


    // Hủy kết bạn

    public function cancelFriendRequest($friendId)
    {
        $friendRequest = Friends::where('user_id', $friendId)
            ->where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($friendRequest) {
            $friendRequest->delete();

            return response()->json([
                'message' => 'Yêu cầu kết bạn đã được huỷ bỏ.'
            ], 200);
        }

        return response()->json([
            'message' => 'Yêu cầu kết bạn không tồn tại.'
        ], 404);
    }


    public function declineRequest($id)
    {
        $friendship = Friends::where('user_id', $id)
            ->where('friend_id', Auth::id())
            ->first();

        if ($friendship) {
            $friendship->status = 'declined';
            $friendship->save();
            return response()->json(['message' => 'Friend request declined.']);
        }

        return response()->json(['message' => 'Friend request not found.'], 404);
    }

    public function unfriend($friendId)
    {
        $friendship = Friends::where(function ($query) use ($friendId) {
            $query->where('user_id', Auth::id())
                ->where('friend_id', $friendId);
        })->orWhere(function ($query) use ($friendId) {
            $query->where('user_id', $friendId)
                ->where('friend_id', Auth::id());
        })->first();

        if ($friendship) {
            $friendship->delete();
            return response()->json(['message' => 'Unfriended successfully.']);
        }

        return response()->json(['message' => 'Friendship not found.'], 404);
    }

    public function getFriendshipStatus($friendId)
    {
        $currentUserId = Auth::id();

        $friendUser = User::where('user_id', $friendId)->first();

        if (!$friendUser) {
            return response()->json([
                'isFriend' => false,
                'isRequested' => false,
                'isRequestedByThem' => false,
            ]);
        }

        $friendIdFromUser = $friendUser->id;

        $friendship = Friends::where(function ($query) use ($friendIdFromUser, $currentUserId) {
            $query->where('user_id', $currentUserId)
                ->where('friend_id', $friendIdFromUser);
        })->orWhere(function ($query) use ($friendIdFromUser, $currentUserId) {
            $query->where('user_id', $friendIdFromUser)
                ->where('friend_id', $currentUserId);
        })->first();

        if ($friendship) {
            return response()->json([
                'isFriend' => $friendship->status === 'accepted',
                'isRequested' => $friendship->status === 'pending' && $friendship->user_id === $currentUserId,
                'isRequestedByThem' => $friendship->status === 'pending' && $friendship->friend_id === $currentUserId,
            ]);
        }

        return response()->json([
            'isFriend' => false,
            'isRequested' => false,
            'isRequestedByThem' => false,
        ]);
    }


    public function getFriendsByUserId($userId)
    {
        // Tìm người dùng dựa trên user_id
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Lấy id của người dùng hiện tại
        $id = $user->id;

        // Lấy danh sách bạn bè có status là 'accepted'
        $friends = Friends::where(function ($query) use ($id) {
            // Khi user_id là $id và trạng thái là 'accepted'
            $query->where('user_id', $id)
                ->where('status', 'accepted');
        })->orWhere(function ($query) use ($id) {
            // Hoặc khi friend_id là $id và trạng thái là 'accepted'
            $query->where('friend_id', $id)
                ->where('status', 'accepted');
        })->get();

        // Duyệt qua danh sách bạn bè để lấy thông tin chi tiết từ bảng users và tính số bạn chung
        $friendList = $friends->map(function ($friend) use ($id) {
            // Xác định bạn bè của người dùng
            $friendId = ($friend->user_id == $id) ? $friend->friend_id : $friend->user_id;

            // Lấy thông tin của người bạn
            $friendUser = User::find($friendId);

            // Tính số bạn chung
            $mutualFriendsCount = $this->countMutualFriends($id, $friendId);

            // Trả về thông tin của bạn bè và số bạn chung
            return [
                'id' => $friendUser->id,
                'user_name' => $friendUser->user_name,
                'user_id' => $friendUser->user_id,
                'avatar' => $friendUser->avatar,
                'mutual_friends' => $mutualFriendsCount
            ];
        });

        return response()->json($friendList); // Trả về danh sách bạn bè dưới dạng JSON
    }

    // Hàm tính số bạn chung
    protected function countMutualFriends($userId1, $userId2)
    {
        // Lấy danh sách bạn bè của userId1
        $friendsOfUser1 = Friends::where(function ($query) use ($userId1) {
            $query->where('user_id', $userId1)
                ->orWhere('friend_id', $userId1);
        })->where('status', 'accepted')->get();

        // Lấy danh sách bạn bè của userId2
        $friendsOfUser2 = Friends::where(function ($query) use ($userId2) {
            $query->where('user_id', $userId2)
                ->orWhere('friend_id', $userId2);
        })->where('status', 'accepted')->get();

        // Lọc ra các bạn chung dựa trên id
        $friendsOfUser1Ids = $friendsOfUser1->map(function ($friend) use ($userId1) {
            return $friend->user_id == $userId1 ? $friend->friend_id : $friend->user_id;
        })->toArray();

        $friendsOfUser2Ids = $friendsOfUser2->map(function ($friend) use ($userId2) {
            return $friend->user_id == $userId2 ? $friend->friend_id : $friend->user_id;
        })->toArray();

        // Tìm các phần tử chung giữa hai mảng bạn bè
        $mutualFriends = array_intersect($friendsOfUser1Ids, $friendsOfUser2Ids);

        // Trả về số lượng bạn chung
        return count($mutualFriends);
    }






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
