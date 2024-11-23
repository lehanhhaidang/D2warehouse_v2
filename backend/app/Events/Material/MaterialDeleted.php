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
            new PrivateChannel('global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'material.deleted';
    }

    // public function broadcastWith(): array
    // {
    //     $users = User::all()->pluck('id');
    //     foreach ($users as $user) {
    //         Notification::create([
    //             'user_id' => $user,
    //             'message' => 'Nguyên vật liệu ' . Material::withTrashed()->find($this->material)->name . ' vừa bị xóa',
    //         ]);
    //     }
    //     return [
    //         'event' => 'material.deleted',
    //         'message' => 'Nguyên vật liệu ' . Material::withTrashed()->find($this->material)->name . ' vừa bị xóa',
    //         'material' => $this->material
    //     ];
    // }

    public function broadcastWith(): array
    {
        $service = app(\App\Services\NotificationService::class);

        $message = $service->formatMessage(
            'material',
            'vừa bị xóa',
            $this->material,
            true
        );

        return $service->notifyAllUsers(
            'material.deleted',
            $message,
            $this->material
        );
    }
}
