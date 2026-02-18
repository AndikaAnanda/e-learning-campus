<?php

namespace App\Events;

use App\Models\Reply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewReplyEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reply;
    /**
     * Create a new event instance.
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('course.' . $this->reply->discussion->course_id);
    }

    public function broadcastAs()
    {
        return 'new.reply';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->reply->id,
            'discussion_id' => $this->reply->discussion_id,
            'user' => [
                'id' => $this->reply->user->id,
                'name' => $this->reply->user->name,
                'role' => $this->reply->user->role,
            ],
            'content' => $this->reply->content,
            'created_at' => $this->reply->created_at->toDateTimeString(),
        ];
    }   
}
