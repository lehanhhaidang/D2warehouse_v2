<?php

namespace App\Events\Material;

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

class MaterialCreated implements ShouldBroadcastNow
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
        return 'material.created';
    }

    // public function broadcastWith(): array
    // {
    //     $users = User::all()->pluck('id');
    //     foreach ($users as $user) {
    //         Notification::create([
    //             'user_id' => $user,
    //             'message' => 'Một nguyên vật liệu mới đã được tạo: ' . $this->material->name,
    //         ]);
    //     }

    //     return [
    //         'event' => 'material.created',
    //         'message' => 'Một nguyên vật liệu mới đã được tạo: ' . $this->material->name,
    //         'material' => $this->material->id
    //     ];
    // }

    public function broadcastWith(): array
    {
        $service = app(\App\Services\NotificationService::class);

        $message = $service->formatMessage(
            'material',
            'vừa được tạo',
            $this->material->id
        );

        return $service->notifyAllUsers(
            'material.created',
            $message,
            $this->material->id
        );
    }
}
