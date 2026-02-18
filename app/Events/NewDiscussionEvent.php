<?php

namespace App\Events;

use App\Models\Discussion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewDiscussionEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $discussion;

    /**
     * Create a new event instance.
     */
    public function __construct(Discussion $discussion)
    {
        $this->discussion = $discussion;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('course.' . $this->discussion->course_id);
    }

    public function broadcastAs()
    {
        return 'new.discussion';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->discussion->id,
            'course_id' => $this->discussion->course_id,
            'user' => [
                'id' => $this->discussion->user->id,
                'name' => $this->discussion->user->name,
                'role' => $this->discussion->user->role,
            ],
            'content' => $this->discussion->content,
            'created_at' => $this->discussion->created_at->toDateTimeString(),
        ];
    }   
}
