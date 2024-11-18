<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Product;
use App\Models\Material;
use App\Models\Propose;
use App\Models\User;

class NotificationService
{
    public function notifyAllUsers(string $event, string $message, int $resourceId): array
    {
        $users = User::all()->pluck('id');

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user,
                'message' => $message,
            ]);
        }

        return [
            'event' => $event,
            'message' => $message,
            'resource_id' => $resourceId,
        ];
    }

    public function formatMessage(
        string $resourceType,
        string $action,
        int $resourceId,
        bool $withTrashed = false
    ): string {
        $resource = null;

        if ($resourceType === 'product') {
            $resource = $withTrashed
                ? Product::withTrashed()->find($resourceId)
                : Product::find($resourceId);
            $resourceType = 'thành phẩm';
        } elseif ($resourceType === 'material') {
            $resource = $withTrashed
                ? Material::withTrashed()->find($resourceId)
                : Material::find($resourceId);
            $resourceType = 'nguyên vật liệu';
        }

        if (!$resource) {
            return ucfirst($resourceType) . ' không tồn tại';
        }

        return ucfirst($resourceType) . " {$resource->name} {$action}";
    }
}
