<?php

namespace App\Events\Material;

use App\Models\Material;
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

class MaterialUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $material;
    public function __construct($material)
    {
        $this->material = $material;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'material.updated';
    }

    public function broadcastWith(): array
    {
        $users = User::all()->pluck('id');
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user,
                'message' => 'Nguyên vật liệu ' . Material::find($this->material)->name . ' vừa được cập nhật',
            ]);
        }
        return [
            'event' => 'material.updated',
            'message' => 'Nguyên vật liệu ' . Material::find($this->material)->name . ' vừa được cập nhật',
            'material' => $this->material
        ];
    }
}
