<?php

namespace App\Events\warehouse;

use App\Models\Category;
use App\Models\Notification;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WarehouseUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $warehouse;
    public function __construct($warehouse)
    {
        $this->warehouse = $warehouse;
    }


    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'warehouse.updated';
    }

    public function broadcastWith(): array
    {
        $users = User::all()->pluck('id');
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user,
                'message' => $this->warehouse->name . ' vừa được cập nhật',
                'url' => '/warehouses'
            ]);
        }
        return [
            'message' => $this->warehouse->name . ' vừa được cập nhật',
            'warehouse' => $this->warehouse
        ];
    }
}
