<?php

namespace App\Enum;


enum ProposeStatus: string
{
    case PENDING_SEND = 'Chờ gửi';
    case PENDING = 'Chờ duyệt';
    case APPROVED = 'Đã duyệt';
    case REJECTED = 'Đã từ chối';
}
