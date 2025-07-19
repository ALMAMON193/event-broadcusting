<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return true;
});
//Broadcast::channel('chat.{receiverId}', function ($user, $receiverId) {
//    return (int) $user->id === (int) $receiverId;
//});
Broadcast::channel('chat.{receiverType}.{receiverId}', function ($user, $receiverType, $receiverId) {
    // Validate user role and id matches the receiverType/id
    return $user->id === (int) $receiverId && $user->role === $receiverType;
});

