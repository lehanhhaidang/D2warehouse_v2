<?php

namespace App\Events\Shelf;

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

class ShelfCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shelf;
    public function __construct($shelf)
    {
        $this->shelf = $shelf;
    }


    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'shelf.created';
    }

    public function broadcastWith(): array
    {
        $users = User::all()->pluck('id');
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user,
                'message' =>
                $this->shelf->name .
                    ' có danh mục ' . Category::find($this->shelf->category_id)->name .
                    ' thuộc ' . Warehouse::find($this->shelf->warehouse_id)->name .
                    ' đã được tạo',
                'url' => '/shelves'
            ]);
        }
        return [
            'message' => $this->shelf->name .
                ' có danh mục ' . Category::find($this->shelf->category_id)->name .
                ' thuộc ' . Warehouse::find($this->shelf->warehouse_id)->name .
                ' đã được tạo',
            'shelf' => $this->shelf->id
        ];
    }
}
