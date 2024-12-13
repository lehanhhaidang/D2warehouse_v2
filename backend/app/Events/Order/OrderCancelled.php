<?php

namespace App\Events\Order;

use App\Models\Notification;
use App\Models\Order;
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

class OrderCancelled implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    /**
     * Create a new event instance.
     */
    public function __construct($order)
    {
        $this->order = Order::find($order);
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
        return 'order.cancelled';
    }

    public function broadcastWith()
    {
        Notification::create([
            'user_id' => Auth::id(),
            'message' => 'Bạn đã từ chối ' . $this->order->name . ' thành công.',
            'url' => '/orders',
        ]);

        $users = User::whereIn('role_id', [2, 3, 4])
            ->where('id', '!=', Auth::id())
            ->get();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => User::find(Auth::id())->name . ' vừa từ chối ' . $this->order->name,
                'url' => '/orders',
            ]);
        }


        return [
            'event' => 'order.cancelled',
            'reviewer_message' => 'Bạn đã từ chối ' . $this->order->name . ' thành công.',
            'public_message' => User::find(Auth::id())->name . ' vừa từ chối ' . $this->order->name,
            'order_id' => $this->order->id,
            'reviewer_id' => Auth::id(),
        ];
    }
}
