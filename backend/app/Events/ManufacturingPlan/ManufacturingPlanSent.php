<?php

namespace App\Events\ManufacturingPlan;

use App\Models\ManufacturingPlan;
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
use Illuminate\Support\Facades\Auth;

class ManufacturingPlanSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $manufacturingPlan;

    /**
     * Create a new event instance.
     */
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
        return 'manufacturing-plan.sent';
    }

    public function broadcastWith()
    {
        $usersToNotify = User::whereIn('role_id', [3])->get();
        foreach ($usersToNotify as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => ManufacturingPlan::find($this->manufacturingPlan)->user->name . ' đã gửi ' . ManufacturingPlan::find($this->manufacturingPlan)->name,
                'url' => '/manufacturing-detail/' . $this->manufacturingPlan,
            ]);
        }

        Notification::create([
            'user_id' => Auth::id(),
            'message' => ManufacturingPlan::find($this->manufacturingPlan)->name . ' của bạn đã được gửi đi, sẽ có thông báo khi báo cáo được phê duyệt.',
            'url' => '/manufacturing-detail/' . $this->manufacturingPlan,
        ]);
        return [
            'event' => 'manufacturing-plan.sent',
            'owner_message' => ManufacturingPlan::find($this->manufacturingPlan)->name . ' của bạn đã được gửi đi, sẽ có thông báo khi kế hoạch được phê duyệt.',
            'other_message' => ManufacturingPlan::find($this->manufacturingPlan)->user->name . ' đã gửi ' . ManufacturingPlan::find($this->manufacturingPlan)->name,
            'manufacturingPlan_id' => $this->manufacturingPlan,
            'manufacturingPlan_created_by' => ManufacturingPlan::find($this->manufacturingPlan)->created_by,
        ];
    }
}
