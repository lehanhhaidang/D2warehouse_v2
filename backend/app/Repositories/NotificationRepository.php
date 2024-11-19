<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Interface\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getNotifications()
    {
        return Notification::where('user_id', Auth::id())->get();
    }
}
