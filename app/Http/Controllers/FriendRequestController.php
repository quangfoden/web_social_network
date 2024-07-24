<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Friend\SendFriendRequest;
use App\Http\Requests\Friend\RespondFriendRequest;
use App\Services\FriendRequestService;

class FriendRequestController extends Controller
{
    //
    protected $friendRequestService;
    public function __construct(FriendRequestService $friendRequestService)
    {
        $this->friendRequestService = $friendRequestService;
    }
    public function getFriends(Request $request)
    {
        $friends = auth()->user()->friends;

        return response()->json(['friends' => $friends]);
    }
    public function sendRequest(SendFriendRequest $request)
    {
        $result = $this->friendRequestService->sendRequest($request->receiver_id);
        return $result;
    }
    public function acceptRequest($id)
    {
        $result = $this->friendRequestService->acceptRequest($id);
        return $result;
    }
    public function declineRequest($id)
    {
        $result = $this->friendRequestService->declineRequest($id);
        return $result;
    }
    public function getFriendRequests()
    {
        $friendRequests = $this->friendRequestService->getFriendRequests();

        return response()->json($friendRequests);
    }

    public function cancelFriendship($id)
    {
        $userId = auth()->id();
        $result = $this->friendRequestService->cancelFriendship($userId, $id);

        return $result;
    }
}
