<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function ($user) {
    if (auth()->check()) {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'avatar' => asset('storage/' . $user->identification->avatar_path),
            'role_label' => $user->identification->role->label(),
            'role_color' => $user->identification->color_username ?? '#ffffff',
        ];
    }
    return false;
});
