<?php

namespace App\Events\ProductReceipt;

use App\Models\Propose;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductReceiptCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $productReceipt;
    public function __construct($productReceipt)
    {
        $this->productReceipt = $productReceipt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('product-receipt'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product-receipt.created';
    }

    public function broadcastWith(): array
    {
        return [
            'manager_message' =>  $this->productReceipt->user->name . ' đã tạo ' . $this->productReceipt->name . ' dựa trên ' . $this->productReceipt->propose->name,
            'employee_message' => 'Bạn đã tạo ' . $this->productReceipt->name . ' dựa trên ' . $this->productReceipt->propose->name . ' thành công',
            'product_receipt_id' => $this->productReceipt->id,
        ];
    }
}
