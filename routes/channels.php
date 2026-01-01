<?php

use Illuminate\Support\Facades\Broadcast;

// Canal privado para cada usuario
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});