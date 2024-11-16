<?php

namespace App\Events\Product;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $product;
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('product'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product.created';
    }

    // public function broadcastWith(): array
    // {

    //     $users = User::all()->pluck('id');
    //     foreach ($users as $user) {
    //         Notification::create([
    //             'user_id' => $user,
    //             'message' => 'Một thành phẩm mới đã được tạo: ' . $this->product->name,
    //         ]);
    //     }
    //     return [
    //         'event' => 'product.created',
    //         'message' => 'Một thành phẩm mới đã được tạo: ' . $this->product->name,
    //         'product' => $this->product->id
    //     ];
    // }

    public function broadcastWith(): array
    {
        $service = app(\App\Services\NotificationService::class);

        $message = $service->formatMessage(
            'product',
            'vừa được tạo',
            $this->product->id
        );

        return $service->notifyAllUsers(
            'product.created',
            $message,
            $this->product->id
        );
    }
}
