<?php

namespace App\Events\ProductExport;

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
            new PrivateChannel('product-Export'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product-Export.created';
    }

    public function broadcastWith(): array
    {
        return [
            'message' =>  $this->productExport->user->name . ' vừa tạo một phiếu xuất kho thành phẩm mới',
            'material_Export_id' => $this->productExport->id,
        ];
    }
}
