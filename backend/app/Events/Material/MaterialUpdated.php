<?php

namespace App\Events\Material;

use App\Models\Material;
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
            new PrivateChannel('material'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'material.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'event' => 'material.updated',
            'message' => 'Nguyên vật liệu ' . Material::find($this->material)->name . ' vừa được cập nhật',
            'material' => $this->material
        ];
    }
}
