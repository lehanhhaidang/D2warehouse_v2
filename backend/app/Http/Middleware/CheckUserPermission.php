<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::user();

        if ($user && $user->role && $user->role->permissions->contains('name', $permission)) {
            return $next($request);
        }

        // Trả về JSON thông báo lỗi khi không có quyền
        return new JsonResponse([
            'error' => 'Unauthorized',
            'message' => 'Bạn không có quyền truy cập nội dung này.'
        ], 403);
    }
}
