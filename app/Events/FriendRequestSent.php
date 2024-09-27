<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FriendRequestSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $friendId;
    public $userId;
    public $userName;
    public  $avatar;
    public  $friendId2;

    /**
     * Create a new event instance.
     */
    public function __construct($friendId, $userId, $userName,$avatar,$friendId2)
    {
        //
        $this->friendId = $friendId;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->avatar = $avatar;
        $this->friendId2 = $friendId2;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->friendId);
    }

    public function broadcastWith()
    {
        Log::info('Broadcasting event to clinet.' . $this->friendId);
        return [
            'message' => 'Bạn nhận được một lời mời kết bạn từ ' . $this->userName,
            'user_id' => $this->userId,
            'friendId2' => $this->friendId2,
            'user_name' => $this->userName,
            'avatar' => $this->avatar,
            'type' => 'đã gửi lời mời kết bạn',
        ];
    }
}
