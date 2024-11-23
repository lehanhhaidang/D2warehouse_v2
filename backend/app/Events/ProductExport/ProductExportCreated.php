<?php

namespace App\Events\ProductExport;

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

class ProductExportCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $productExport;
    public function __construct($productExport)
    {
        $this->productExport = $productExport;
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
        return 'product-export.created';
    }

    public function broadcastWith(): array
    {
        Notification::create([
            'user_id' => $this->productExport->created_by,
            'message' => 'Bạn đã tạo ' . $this->productExport->name . ' dựa trên ' . $this->productExport->propose->name . ' thành công',
        ]);

        $ids = User::where(function ($query) {
            $query->where('role_id', 2)
                ->orWhere('role_id', 3)
                ->orWhere('role_id', 4);
        })
            ->where('id', '!=', $this->productExport->created_by)
            ->pluck('id')
            ->toArray();

        foreach ($ids as $id) {
            Notification::create([
                'user_id' => $id,
                'message' => $this->productExport->user->name . ' đã tạo ' . $this->productExport->name . ' dựa trên ' . $this->productExport->propose->name,
            ]);
        }
        return [
            'event' => 'product-export.created',
            'manager_message' =>  $this->productExport->user->name . ' đã tạo ' . $this->productExport->name . ' dựa trên ' . $this->productExport->propose->name,
            'employee_message' => 'Bạn đã tạo ' . $this->productExport->name . ' dựa trên ' . $this->productExport->propose->name . ' thành công',
            'product_receipt_id' => $this->productExport->id,
            'product_receipt_created_by' => $this->productExport->created_by,
        ];
    }
}
