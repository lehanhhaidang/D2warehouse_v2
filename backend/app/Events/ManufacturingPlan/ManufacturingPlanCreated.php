<?php

namespace App\Events\ManufacturingPlan;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManufacturingPlanCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $manufacturingPlan;
    public function __construct($manufacturingPlan)
    {
        $this->manufacturingPlan = $manufacturingPlan;
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
        return 'manufacturing-plan.created';
    }



    public function broadcastWith()
    {
        Notification::create([
            'user_id' => $this->manufacturingPlan->created_by,
            'message' => 'Bạn đã tạo ' . $this->manufacturingPlan->name . ' thành công, hãy kiểm tra và gửi cho cấp trên',
            'url' => '/manufacturing-detail/' . $this->manufacturingPlan->id,
        ]);
        return [
            'event' => 'manufacturing-plan.created',
            'owner_message' => 'Bạn đã tạo ' . $this->manufacturingPlan->name . ' thành công, hãy kiểm tra và gửi cho cấp trên',
            'manufacturingPlan_id' => $this->manufacturingPlan->id,
            'manufacturingPlan_created_by' => $this->manufacturingPlan->created_by,
        ];
    }
}
