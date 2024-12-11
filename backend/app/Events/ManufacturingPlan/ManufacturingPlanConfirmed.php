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

class ManufacturingPlanConfirmed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $manufacturingPlan;

    /**
     * Create a new event instance.
     */
    public function __construct($manufacturingPlan)
    {
        $this->manufacturingPlan = ManufacturingPlan::find($manufacturingPlan);
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
        return 'manufacturing-plan.confirmed';
    }

    public function broadcastWith()
    {
        $userToNotify = User::whereIn('role_id', [2, 3, 4])
            ->where('id', '!=', Auth::id())
            ->get();
        foreach ($userToNotify as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => User::find(Auth::id())->name . ' vừa xét duyệt ' . $this->manufacturingPlan->name . ' của ' . User::find($this->manufacturingPlan->created_by)->name,
                'url' => '/manufacturing-detail/' . $this->manufacturingPlan->id,
            ]);
        }

        Notification::create([
            'user_id' => Auth::id(),
            'message' => 'Bạn đã xét duyệt ' . $this->manufacturingPlan->name . ' của ' . User::find($this->manufacturingPlan->created_by)->name . ' thành công',
            'url' => '/manufacturing-detail/' . $this->manufacturingPlan->id,
        ]);

        Notification::create([
            'user_id' => $this->manufacturingPlan->created_by,
            'message' => User::find(Auth::id())->name . ' đã xét duyệt ' . $this->manufacturingPlan->name . ' của bạn',
            'url' => '/manufacturing-detail/' . $this->manufacturingPlan->id,
        ]);
        return [
            'event' => 'manufacturing-plan.confirmed',
            'owner_message' => User::find(Auth::id())->name . ' đã xét duyệt ' . $this->manufacturingPlan->name . ' của bạn',
            'reviewer_message' => 'Bạn đã xét duyệt ' . $this->manufacturingPlan->name . ' của ' . User::find($this->manufacturingPlan->created_by)->name . ' thành công',
            'public_message' => User::find(Auth::id())->name . ' vừa xét duyệt ' . $this->manufacturingPlan->name . ' của ' . User::find($this->manufacturingPlan->created_by)->name,
            'manufacturingPlan' => $this->manufacturingPlan->id,
            'manufacturingPlan_created_by' => $this->manufacturingPlan->created_by,
            'reviewer_id' => Auth::id(),
        ];
    }
}
