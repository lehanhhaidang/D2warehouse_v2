<?php

namespace App\Events\MaterialReceipt;

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
            new PrivateChannel('global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'material-receipt.created';
    }

    public function broadcastWith(): array
    {
        Notification::create([
            'user_id' => $this->materialReceipt->created_by,
            'message' => 'Bạn đã tạo ' . $this->materialReceipt->name . ' dựa trên ' . $this->materialReceipt->propose->name . ' thành công',
        ]);

        $ids = User::where(function ($query) {
            $query->where('role_id', 2)
                ->orWhere('role_id', 3)
                ->orWhere('role_id', 4);
        })
            ->where('id', '!=', $this->materialReceipt->created_by)
            ->pluck('id')
            ->toArray();

        foreach ($ids as $id) {
            Notification::create([
                'user_id' => $id,
                'message' => $this->materialReceipt->user->name . ' đã tạo ' . $this->materialReceipt->name . ' dựa trên ' . $this->materialReceipt->propose->name,
            ]);
        }
        return [
            'event' => 'material-receipt.created',
            'manager_message' =>  $this->materialReceipt->user->name . ' đã tạo ' . $this->materialReceipt->name . ' dựa trên ' . $this->materialReceipt->propose->name,
            'employee_message' => 'Bạn đã tạo ' . $this->materialReceipt->name . ' dựa trên ' . $this->materialReceipt->propose->name . ' thành công',
            'product_receipt_id' => $this->materialReceipt->id,
            'product_receipt_created_by' => $this->materialReceipt->created_by,
        ];
    }
}
