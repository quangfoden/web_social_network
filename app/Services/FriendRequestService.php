<?php

namespace App\Services;

use App\Models\FriendRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Friend;
use App\Models\Friends;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FriendRequestService
{
    protected $firebase;
    public function __construct()
    {
        $firebaseFactory = (new Factory)
            ->withServiceAccount(config('services.firebase.credentials.file'))
            ->withDatabaseUri(config('services.firebase.database_url'));

        $this->firebase = $firebaseFactory->createDatabase();
    }

    public function sendRequest($receiverId)
    {
        try {
            $existingRequest = FriendRequest::where('receiver_id', Auth::id())
                ->where('sender_id', $receiverId)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return response()->json(['message' => 'Người này đã gửi yêu cầu kết bạn tới bạn.'], 200);
            }

            $friendRequest = FriendRequest::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $receiverId,
                'status' => 'pending',
            ]);
            $database = $this->firebase;
            $newFriendRequestRef = $database->getReference('friendRequests')->push([
                'sender_id' => Auth::id(),
                'receiver_id' => $receiverId,
                'status' => 'pending',
            ]);
            return response()->json(['message' => 'Đã gửi yêu cầu kết bạn thành công.'], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => 'Đã xảy ra lỗi khi gửi yêu cầu kết bạn.'], 500);
        }
    }


    public function acceptRequest($id)
    {
        try {
            $friendRequest = FriendRequest::where('receiver_id', Auth::id())
                ->where('sender_id', $id)
                ->firstOrFail();
            $friendRequest->update(['status' => 'accepted']);
            $senderId = $friendRequest->sender_id;
            auth()->user()->friends()->attach($senderId);
            User::find($senderId)->friends()->attach(auth()->id());

            $database = $this->firebase;
            $friendRequestRef = $database->getReference('friendRequests')
                ->orderByChild('sender_id')
                ->equalTo($senderId)
                ->getSnapshot();

            if ($friendRequestRef->exists()) {
                foreach ($friendRequestRef->getValue() as $key => $value) {
                    if ($value['receiver_id'] == Auth::id() && $value['status'] == 'pending') {
                        $database->getReference('friendRequests/' . $key)->update(['status' => 'accepted']);
                    }
                }
            }
            // Thêm bạn bè vào bảng friends trong Firebase
            $database->getReference('friends/' . Auth::id())->push([
                'user_id' => Auth::id(),
                'friend_id' => $senderId,
            ]);
            $database->getReference('friends/' . $senderId)->push([
                'user_id' => $senderId,
                'friend_id' => Auth::id(),
            ]);

            return response()->json(['message' => 'Yêu cầu kết bạn đã được chấp nhận.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Không tìm thấy yêu cầu kết bạn này.'], 404);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['error' => 'Đã xảy ra lỗi khi chấp nhận yêu cầu kết bạn.'], 500);
        }
    }

    public function declineRequest($id)
    {
        try {
            $friendRequest = FriendRequest::where('receiver_id', Auth::id())
                ->where('sender_id', $id)
                ->where('status', 'pending')
                ->firstOrFail();

            $friendRequest->update(['status' => 'declined']);
            $senderId = $friendRequest->sender_id;
            $database = $this->firebase;
            $friendRequestRef = $database->getReference('friendRequests')
                ->orderByChild('sender_id')
                ->equalTo($senderId)
                ->getSnapshot();

            if ($friendRequestRef->exists()) {
                foreach ($friendRequestRef->getValue() as $key => $value) {
                    if ($value['receiver_id'] == Auth::id() && $value['status'] == 'pending') {
                        $database->getReference('friendRequests/' . $key)->update(['status' => 'declined']);
                    }
                }
            }
            return response()->json(['message' => 'Yêu cầu kết bạn đã bị từ chối.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Không tìm thấy yêu cầu kết bạn này.'], 404);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json(['error' => 'Đã xảy ra lỗi khi từ chối yêu cầu kết bạn.'], 500);
        }
    }

    public function getFriendRequests()
    {
        $receivedRequests = FriendRequest::where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->with('sender')
            ->get();

        $sentRequests = FriendRequest::where('sender_id', Auth::id())
            ->where('status', 'pending')
            ->with('receiver')
            ->get();

        return [
            'received_requests' => $receivedRequests,
            'sent_requests' => $sentRequests,
        ];
    }


    public function cancelFriendship($userId, $friendId)
    {
        try {
            // Xóa yêu cầu kết bạn từ MySQL
            $friendshipDeleted = Friends::where(function ($query) use ($userId, $friendId) {
                $query->where('user_id', $userId)
                    ->where('friend_id', $friendId);
            })
                ->orWhere(function ($query) use ($userId, $friendId) {
                    $query->where('user_id', $friendId)
                        ->where('friend_id', $userId);
                })
                ->delete();

            // Xóa yêu cầu kết bạn từ MySQL
            $requestsDeleted = FriendRequest::where(function ($query) use ($userId, $friendId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $friendId);
            })
                ->orWhere(function ($query) use ($userId, $friendId) {
                    $query->where('sender_id', $friendId)
                        ->where('receiver_id', $userId);
                })
                ->delete();

            $database = $this->firebase;

            // Xóa yêu cầu kết bạn từ Firebase
            $friendRequestRef = $database->getReference('friendRequests')
                ->orderByChild('sender_id')
                ->equalTo($friendId)
                ->getSnapshot();
            if ($friendRequestRef->exists()) {
                foreach ($friendRequestRef->getValue() as $key => $value) {
                    if ($value['receiver_id'] == $userId) {
                        $database->getReference('friendRequests/' . $key)->remove();
                    }
                }
            }
            $_friendRequestRef = $database->getReference('friendRequests')
                ->orderByChild('receiver_id')
                ->equalTo($friendId)
                ->getSnapshot();
            if ($_friendRequestRef->exists()) {
                foreach ($_friendRequestRef->getValue() as $key => $value) {
                    if ($value['receiver_id'] == $friendId) {
                        $database->getReference('friendRequests/' . $key)->remove();
                    }
                }
            }

            $friendRequestRefReverse = $database->getReference('friendRequests')
                ->orderByChild('sender_id')
                ->equalTo($userId)
                ->getSnapshot();
            if ($friendRequestRefReverse->exists()) {
                foreach ($friendRequestRefReverse->getValue() as $key => $value) {
                    if ($value['receiver_id'] == $friendId) {
                        $database->getReference('friendRequests/' . $key)->remove();
                    }
                }
            }
            $_friendRequestRefReverse = $database->getReference('friendRequests')
                ->orderByChild('receiver_id')
                ->equalTo($userId)
                ->getSnapshot();
            if ($_friendRequestRefReverse->exists()) {
                foreach ($_friendRequestRefReverse->getValue() as $key => $value) {
                    if ($value['receiver_id'] == $userId) {
                        $database->getReference('friendRequests/' . $key)->remove();
                    }
                }
            }

            // Xoá bạn bè từ firebase
            $friendsRef = $database->getReference('friends/' . $userId)
                ->orderByChild('user_id')
                ->equalTo($userId)
                ->getSnapshot();
            if ($friendsRef->exists()) {
                foreach ($friendsRef->getValue() as $key => $value) {
                    $database->getReference('friends/' . $userId . '/' . $key)->remove();
                }
            }

            $friendsRefReverse = $database->getReference('friends/' . $friendId)
                ->orderByChild('friend_id')
                ->equalTo($userId)
                ->getSnapshot();
            if ($friendsRefReverse->exists()) {
                foreach ($friendsRefReverse->getValue() as $key => $value) {
                    $database->getReference('friends/' . $friendId . '/' . $key)->remove();
                }
            }

            if ($friendshipDeleted > 0) {
                return response()->json(['message' => 'Huỷ kết bạn thành công.']);
            } elseif ($requestsDeleted > 0) {
                return response()->json(['message' => 'Huỷ gửi lời mời kết bạn thành công']);
            } else {
                return response()->json(['message' => 'Các bạn hiện không phải là bạn bè.']);
            }
        } catch (\Exception $e) {
            dd($e);
            error_log($e->getMessage());
            return response()->json(['error' => 'Đã xảy ra lỗi khi huỷ kết bạn.'], 500);
        }
    }
}
