<?php

namespace App\Events\MaterialExport;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaterialExportCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $materialExport;
    public function __construct($materialExport)
    {
        $this->materialExport = $materialExport;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('material-export'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'material-export.created';
    }

    public function broadcastWith(): array
    {
        return [
            'manager_message' =>  $this->materialExport->user->name . ' đã tạo ' . $this->materialExport->name . ' dựa trên ' . $this->materialExport->propose->name,
            'employee_message' => 'Bạn đã tạo ' . $this->materialExport->name . ' dựa trên ' . $this->materialExport->propose->name . ' thành công',
            'material_Export_id' => $this->materialExport->id,
        ];
    }
}
