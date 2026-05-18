<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enum\Role;

class UpgradeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'requested_role',
        'status',
    ];

    protected $casts = [
        'requested_role' => Role::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
