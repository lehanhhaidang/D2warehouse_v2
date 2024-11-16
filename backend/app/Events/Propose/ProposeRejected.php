<?php

namespace App\Events\Propose;

use App\Models\Notification;
use App\Models\Propose;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ProposeRejected implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $propose;
    /**
     * Create a new event instance.
     */
    public function __construct($propose)
    {
        $this->propose = Propose::find($propose);
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
        return 'propose.rejected';
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'event' => 'propose.accepted',
    //         'reviewer_message' => 'Bạn đã từ chối ' . $this->propose->name . ' thành công.',
    //         'owner_message' => $this->propose->name . ' của bạn đã bị ' . User::find(Auth::id())->name . ' từ chối.',
    //         'propose_id' => $this->propose->id,
    //         'propose_created_by' => $this->propose->created_by,
    //         'reviewer_id' => Auth::id(),
    //     ];
    // }

    public function broadcastWith()
    {
        Notification::create([
            'user_id' => $this->propose->created_by,
            'message' => $this->propose->name . ' của bạn đã bị từ chối bởi ' . User::find(Auth::id())->name . '.',
        ]);
        Notification::create([
            'user_id' => Auth::id(),
            'message' => 'Bạn đã từ chối ' . $this->propose->name . ' của ' . User::find($this->propose->id)->name . '.',
        ]);

        return [
            'event' => 'propose.accepted',
            'reviewer_message' => 'Bạn đã từ chối ' . $this->propose->name . ' của ' . User::find($this->propose->id)->name . '.',
            'owner_message' => $this->propose->name . ' của bạn đã bị từ chối bởi ' . User::find(Auth::id())->name . '.',
            'propose_id' => $this->propose->id,
            'propose_created_by' => $this->propose->created_by,
            'reviewer_id' => Auth::id(),
        ];
    }
}
