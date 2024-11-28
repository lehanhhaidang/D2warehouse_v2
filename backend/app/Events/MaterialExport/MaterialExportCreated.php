<?php

namespace App\Events\MaterialExport;

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

class MaterialExportCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $materialExport;
    public function __construct($materialExport)
    {
        $this->materialExport = $materialExport;
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
        return 'material-export.created';
    }

    public function broadcastWith(): array
    {
        Notification::create([
            'user_id' => $this->materialExport->created_by,
            'message' => 'Bạn đã tạo ' . $this->materialExport->name . ' dựa trên ' . $this->materialExport->propose->name . ' thành công',
            'url' => '/notes?filter=xuất nguyên vật liệu',
        ]);

        $ids = User::where(function ($query) {
            $query->where('role_id', 2)
                ->orWhere('role_id', 3)
                ->orWhere('role_id', 4);
        })
            ->where('id', '!=', $this->materialExport->created_by)
            ->pluck('id')
            ->toArray();

        foreach ($ids as $id) {
            Notification::create([
                'user_id' => $id,
                'message' => $this->materialExport->user->name . ' đã tạo ' . $this->materialExport->name . ' dựa trên ' . $this->materialExport->propose->name,
                'url' => '/notes?filter=xuất nguyên vật liệu',
            ]);
        }
        return [
            'event' => 'material-export.created',
            'manager_message' =>  $this->materialExport->user->name . ' đã tạo ' . $this->materialExport->name . ' dựa trên ' . $this->materialExport->propose->name,
            'employee_message' => 'Bạn đã tạo ' . $this->materialExport->name . ' dựa trên ' . $this->materialExport->propose->name . ' thành công',
            'product_receipt_id' => $this->materialExport->id,
            'product_receipt_created_by' => $this->materialExport->created_by,
        ];
    }
}
