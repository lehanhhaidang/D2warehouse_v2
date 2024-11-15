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

class MaterialDeleted implements ShouldBroadcastNow
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
        return 'material.deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'event' => 'material.deleted',
            'message' => 'Nguyên vật liệu ' . Material::withTrashed()->find($this->material)->name . ' vừa bị xóa',
            'material' => $this->material
        ];
    }
}
