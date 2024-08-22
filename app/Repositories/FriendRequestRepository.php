<?php

namespace App\Repositories;

use App\Models\FriendRequest;

class FriendRequestRepository
{
    public function create($data)
    {
        return FriendRequest::create($data);
    }

    public function find($id)
    {
        return FriendRequest::find($id);
    }

    public function findByReceiver($receiverId)
    {
        return FriendRequest::where('receiver_id', $receiverId)->get();
    }

    public function findBySender($senderId)
    {
        return FriendRequest::where('sender_id', $senderId)->get();
    }
}
