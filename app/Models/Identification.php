<?php

namespace App\Models;

use App\Enum\Role;
use App\Policies\PostProfile;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UsePolicy(PostProfile::class)]
class Identification extends Model
{

    protected $fillable = [
        'user_id',
        'role',
        'expired_at',
        'has_custom_color_unlocked',
        'color_username',
        'avatar_path',
        'website',
        'bio',
        'username_changes',
    ];

    protected $casts = [
        'role' => Role::class,
        'expired_at' => 'datetime',
        'has_custom_color_unlocked' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
