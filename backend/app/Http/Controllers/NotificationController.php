<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\NotificationRepositoryInterface;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationRepository, $notificationService;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
        NotificationService $notificationService
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->notificationService = $notificationService;
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

    public function updateStatus()
    {
        try {
            $this->notificationService->updateStatus();
            return response()->json(['message' => 'Cập nhật trạng thái thông báo thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
