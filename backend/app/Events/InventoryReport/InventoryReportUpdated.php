<?php

namespace App\Events\InventoryReport;

use App\Models\InventoryReport;
use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryReportUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inventoryReport;
    /**
     * Create a new event instance.
     */
    public function __construct($inventoryReport)
    {
        $this->inventoryReport = $inventoryReport;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'inventory-report.updated';
    }

    public function broadcastWith(): array
    {
        Notification::create([
            'user_id' => InventoryReport::find($this->inventoryReport)->created_by,
            'message' => InventoryReport::find($this->inventoryReport)->name . ' đã được cập nhật thành công.',
            'url' => '/inventory-detail/' . $this->inventoryReport,
        ]);
        return [
            'event' => 'inventory-report.updated',
            'owner_message' => InventoryReport::find($this->inventoryReport)->name . ' đã được cập nhật thành công.',
            'inventoryReport_id' => $this->inventoryReport,
            'inventoryReport_created_by' => InventoryReport::find($this->inventoryReport)->created_by,
        ];
    }
}
