<?php

namespace App\Events\InventoryReport;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryReportCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inventoryReport;
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
            new PrivateChannel('inventory-report'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'inventory-report.created';
    }

    public function broadcastWith()
    {
        return [
            'event' => 'inventory-report.created',
            'owner_message' => 'Bạn đã tạo ' . $this->inventoryReport->name . ' thành công, hãy kiểm tra và gửi cho cấp trên',
            'inventoryReport_id' => $this->inventoryReport->id,
            'inventoryReport_created_by' => $this->inventoryReport->created_by,
        ];
    }
}
