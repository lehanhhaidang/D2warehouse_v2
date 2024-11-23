<?php

namespace App\Events\InventoryReport;

use App\Models\InventoryReport;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryReportDeleted implements ShouldBroadcastNow
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
        return 'inventory-report.deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'event' => 'inventory-report.deleted',
            'owner_message' => InventoryReport::withTrashed()->find($this->inventoryReport)->name . ' đã được xóa thành công.',
            'inventoryReport_id' => $this->inventoryReport,
            'inventoryReport_created_by' => InventoryReport::withTrashed()->find($this->inventoryReport)->created_by,
        ];
    }
}
