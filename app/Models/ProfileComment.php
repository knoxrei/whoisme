<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileComment extends Model
{
    protected $fillable = ['user_id', 'profile_user_id', 'content'];

    /**
     * Get the user who authored the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user whose profile the comment was written on.
     */
    public function profileUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profile_user_id');
    }
}
