<?php

namespace App\Events\Propose;

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

class ProposeSent implements ShouldBroadcastNow
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
        return 'propose.sent';
    }

    public function broadcastWith()
    {

        $message1 = $this->propose->name . ' của bạn đã được gửi đi, sẽ có thông báo khi đề xuất được phê duyệt.';
        $message2 = User::find($this->propose->created_by)->name . ' đã gửi ' . $this->propose->name;


        return [
            'message' => [
                'msg1' => $message1,
                'msg2' => $message2,
            ],
            'propose' => [
                'id' => $this->propose->id,
                'name' => $this->propose->name,
                'created_by' => $this->propose->created_by,
            ],
        ];
    }
}
