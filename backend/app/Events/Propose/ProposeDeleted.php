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

class ProposeDeleted implements ShouldBroadcastNow
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
        return 'propose.deleted';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        Notification::create([
            'user_id' => Propose::withTrashed()->find($this->propose)->created_by,
            'message' => 'Bạn vừa xóa ' . Propose::withTrashed()->find($this->propose)->name,
        ]);
        return [
            'message' => 'Bạn vừa xóa ' . Propose::withTrashed()->find($this->propose)->name,
            'propose_id' => $this->propose,
            'propose_created_by' => Propose::withTrashed()->find($this->propose)->created_by,
        ];
    }
}
