<?php

namespace App\Events\InventoryReport;

use App\Models\InventoryReport;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class InventoryReportRejected implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inventoryReport;

    /**
     * Create a new event instance.
     */
    public function __construct($inventoryReport)
    {
        $this->inventoryReport = InventoryReport::find($inventoryReport);
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
        return 'inventory-report.rejected';
    }

    public function broadcastWith()
    {
        return [
            'event' => 'inventory-report.rejected',
            'owner_message' => User::find(Auth::id())->name . ' đã từ chối ' . $this->inventoryReport->name . ' của bạn',
            'reviewer_message' => 'Bạn đã từ chối ' . $this->inventoryReport->name . ' của ' . User::find($this->inventoryReport->created_by)->name . ' thành công',
            'inventoryReport' => $this->inventoryReport,
            'inventoryReport_created_by' => $this->inventoryReport->created_by,
            'reviewer_id' => Auth::id(),
        ];
    }
}
