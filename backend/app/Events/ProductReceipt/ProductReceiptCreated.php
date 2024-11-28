<?php

namespace App\Events\ProductReceipt;

use App\Models\Notification;
use App\Models\Propose;
use App\Models\User;
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
            new PrivateChannel('global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product-receipt.created';
    }

    public function broadcastWith(): array
    {
        Notification::create([
            'user_id' => $this->productReceipt->created_by,
            'message' => 'Bạn đã tạo ' . $this->productReceipt->name . ' dựa trên ' . $this->productReceipt->propose->name . ' thành công',
            'url' => '/notes?filter=nhập thành phẩm',
        ]);

        $ids = User::where(function ($query) {
            $query->where('role_id', 2)
                ->orWhere('role_id', 3)
                ->orWhere('role_id', 4);
        })
            ->where('id', '!=', $this->productReceipt->created_by)
            ->pluck('id')
            ->toArray();

        foreach ($ids as $id) {
            Notification::create([
                'user_id' => $id,
                'message' => $this->productReceipt->user->name . ' đã tạo ' . $this->productReceipt->name . ' dựa trên ' . $this->productReceipt->propose->name,
                'url' => '/notes?filter=nhập thành phẩm',
            ]);
        }
        return [
            'event' => 'product-receipt.created',
            'manager_message' =>  $this->productReceipt->user->name . ' đã tạo ' . $this->productReceipt->name . ' dựa trên ' . $this->productReceipt->propose->name,
            'employee_message' => 'Bạn đã tạo ' . $this->productReceipt->name . ' dựa trên ' . $this->productReceipt->propose->name . ' thành công',
            'product_receipt_id' => $this->productReceipt->id,
            'product_receipt_created_by' => $this->productReceipt->created_by,
        ];
    }
}
