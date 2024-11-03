<?php

namespace App\Events\MaterialReceipt;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaterialReceiptCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $materialReceipt;
    public function __construct($materialReceipt)
    {
        $this->materialReceipt = $materialReceipt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('material-receipt'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'material-receipt.created';
    }

    public function broadcastWith(): array
    {
        return [
            'message' =>  $this->materialReceipt->user->name . ' vừa tạo một phiếu nhập kho nguyên vật liệu mới',
            'material_receipt_id' => $this->materialReceipt->id,
        ];
    }
}
