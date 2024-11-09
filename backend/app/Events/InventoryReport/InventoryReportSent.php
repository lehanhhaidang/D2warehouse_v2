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
use Illuminate\Support\Facades\Auth;

class InventoryReportSent implements ShouldBroadcastNow
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
            new PrivateChannel('inventory-report'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'inventory-report.sent';
    }

    public function broadcastWith()
    {
        if (InventoryReport::find($this->inventoryReport)->user->id ==  Auth::id()) {
            return [
                'message' => 'Bạn vừa gửi ' . InventoryReport::find($this->inventoryReport)->name,
                'inventoryReport' => $this->inventoryReport,
            ];
        }
        return [
            'message' => InventoryReport::find($this->inventoryReport)->user->name . ' vừa gửi ' . InventoryReport::find($this->inventoryReport)->name,
            'inventoryReport' => $this->inventoryReport,
        ];
    }
}
