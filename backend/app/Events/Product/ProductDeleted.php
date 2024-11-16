<?php

namespace App\Events\Product;

use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
        return 'product.deleted';
    }

    // public function broadcastWith(): array
    // {
    //     $users = User::all()->pluck('id');
    //     foreach ($users as $user) {
    //         Notification::create([
    //             'user_id' => $user,
    //             'message' => 'Thành phẩm ' . Product::withTrashed()->find($this->product)->name . ' vừa bị xóa',
    //         ]);
    //     }
    //     return [
    //         'event' => 'product.deleted',
    //         'message' => 'Thành phẩm ' . Product::withTrashed()->find($this->product)->name . ' vừa bị xóa',
    //         'product' => $this->product
    //     ];
    // }

    public function broadcastWith(): array
    {
        $service = app(\App\Services\NotificationService::class);

        $message = $service->formatMessage(
            'product',
            'vừa bị xóa',
            $this->product,
            true
        );

        return $service->notifyAllUsers(
            'product.deleted',
            $message,
            $this->product,
        );
    }
}
