<?php

namespace App\Events\Propose;

use App\Models\Notification;
use App\Models\Propose;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProposeUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $propose;
    /**
     * Create a new event instance.
     */
    public function __construct($propose)
    {
        $this->propose = $propose;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('propose'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'propose.updated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        Notification::create([
            'user_id' => $this->propose->created_by,
            'message' => $this->propose->name . ' của bạn đã được cập nhật thành công.',
            'url' => '/manager-detail/' . $this->propose->id,
        ]);
        return [
            'event' => 'propose.updated',
            'message' =>  $this->propose->name . ' của bạn đã được cập nhật thành công.',
            'propose_id' => $this->propose->id,
            'propose_created_by' => $this->propose->created_by,
        ];
    }
}
