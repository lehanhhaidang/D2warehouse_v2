<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\NotificationRepositoryInterface;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function index()
    {
        try {
            $notifications = $this->notificationRepository->getNotifications();
            return response()->json($notifications, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
