<?php

namespace App\Events\manufacturingPlan;

use App\Models\ManufacturingPlan;
use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManufacturingPlanDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $manufacturingPlan;
    /**
     * Create a new event instance.
     */
    public function __construct($manufacturinPlan)
    {
        $this->manufacturingPlan = $manufacturinPlan;
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
        return 'manufacturing-plan.deleted';
    }

    public function broadcastWith(): array
    {
        Notification::create([
            'user_id' => manufacturingPlan::withTrashed()->find($this->manufacturingPlan)->created_by,
            'message' => manufacturingPlan::withTrashed()->find($this->manufacturingPlan)->name . ' đã được xóa thành công.',
            'url' => '/manufacturing-plan',
        ]);
        return [
            'event' => 'manufacturing-plan.deleted',
            'owner_message' => manufacturingPlan::withTrashed()->find($this->manufacturingPlan)->name . ' đã được xóa thành công.',
            'manufacturingPlan_id' => $this->manufacturingPlan,
            'manufacturingPlan_created_by' => manufacturingPlan::withTrashed()->find($this->manufacturingPlan)->created_by,
        ];
    }
}
