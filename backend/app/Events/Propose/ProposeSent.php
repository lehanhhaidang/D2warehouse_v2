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
            new PrivateChannel('global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'propose.sent';
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'event' => 'propose.sent',
    //         'owner_message' => $this->propose->name . ' của bạn đã được gửi đi, sẽ có thông báo khi đề xuất được phê duyệt.',
    //         'other_message' => User::find($this->propose->created_by)->name . ' đã gửi ' . $this->propose->name,
    //         'propose_id' => $this->propose->id,
    //         'propose_created_by' => $this->propose->created_by,  // Đảm bảo tên trường đúng
    //     ];
    // }

    public function broadcastWith()
    {
        // Lưu thông báo cho người tạo đề xuất (owner_message)
        Notification::create([
            'user_id' => Auth::id(),  // Người gửi đề xuất (chủ sở hữu)
            'message' => $this->propose->name . ' của bạn đã được gửi đi, sẽ có thông báo khi đề xuất được phê duyệt.',
            'url' => '/detail-propose/' . $this->propose->id,
        ]);


        if ($this->propose->type === 'DXNTP' || $this->propose->type === 'DXXTP') {
            $usersToNotify = User::whereIn('role_id', [2, 3])->get();
        } else {
            $usersToNotify = User::whereIn('role_id', [3])->get();
        }


        foreach ($usersToNotify as $user) {
            Notification::create([
                'user_id' => $user->id,  // Người nhận thông báo
                'message' => User::find($this->propose->created_by)->name . ' đã gửi ' . $this->propose->name,
                'url' => '/detail-propose/' . $this->propose->id,
            ]);
        }

        return [
            'event' => 'propose.sent',
            'owner_message' => $this->propose->name . ' của bạn đã được gửi đi, sẽ có thông báo khi đề xuất được phê duyệt.',
            'other_message' => User::find($this->propose->created_by)->name . ' đã gửi ' . $this->propose->name,
            'propose_id' => $this->propose->id,
            'propose_created_by' => $this->propose->created_by,
            'propose_type' => $this->propose->type,
        ];
    }
}
