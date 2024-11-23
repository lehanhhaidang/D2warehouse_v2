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

class ProposeAccepted implements ShouldBroadcastNow
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
        return 'propose.accepted';
    }

    public function broadcastWith()
    {
        Notification::create([
            'user_id' => $this->propose->created_by,
            'message' => $this->propose->name . ' của bạn đã được ' . User::find(Auth::id())->name . ' xét duyệt. Đề xuất này đã có thể tạo phiếu.',
            'url' => '/manager-detail/' . $this->propose->id,
        ]);
        Notification::create([
            'user_id' => Auth::id(),
            'message' => 'Bạn đã xét duyệt ' . $this->propose->name . ' của ' . User::find($this->propose->created_by)->name . ' thành công.',
            'url' => '/detail-propose/' . $this->propose->id,
        ]);

        if ($this->propose->assigned_to != null) {
            Notification::create([
                'user_id' => $this->propose->assigned_to,
                'message' => User::find($this->propose->created_by)->name . ' đã điều phối cho bạn xử lý ' . $this->propose->name . '.',
                'url' => '/manager-detail/' . $this->propose->id,
            ]);
        }

        return [
            'event' => 'propose.accepted',
            'reviewer_message' => 'Bạn đã xét duyệt ' . $this->propose->name . ' của ' . User::find($this->propose->created_by)->name . ' thành công.',
            'assigned_to' => $this->propose->assigned_to,
            'owner_message' => $this->propose->name . ' của bạn đã được ' . User::find(Auth::id())->name . ' xét duyệt. Đề xuất này đã có thể tạo phiếu.',
            'employee_message' => User::find($this->propose->created_by)->name . ' đã điều phối cho bạn xử lý ' . $this->propose->name . '.',
            'propose_id' => $this->propose->id,
            'propose_created_by' => $this->propose->created_by,
            'reviewer_id' => Auth::id(),
        ];
    }
}
