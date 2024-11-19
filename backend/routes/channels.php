<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('propose', function ($user) {
//     return $user;
// });

Broadcast::channel('product', function ($user) {
    return in_array($user->role_id, [1, 2, 3, 4]);
});

Broadcast::channel('material', function ($user) {
    return in_array($user->role_id, [1, 2, 3, 4]);
});

Broadcast::channel('shelf', function ($user) {
    return in_array($user->role_id, [1, 2, 3, 4]);
});

Broadcast::channel('propose', function ($user) {
    return in_array($user->role_id, [2, 3, 4]);
});

Broadcast::channel('product-receipt', function ($user) {
    return in_array($user->role_id, [2, 3, 4]);
});

Broadcast::channel('material-receipt', function ($user) {
    return in_array($user->role_id, [2, 3, 4]);
});

Broadcast::channel('product-export', function ($user) {
    return in_array($user->role_id, [2, 3, 4]);
});

Broadcast::channel('material-export', function ($user) {
    return in_array($user->role_id, [2, 3, 4]);
});
