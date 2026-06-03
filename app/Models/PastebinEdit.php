<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PastebinEdit extends Model
{
    protected $fillable = [
        'pastebin_id',
        'user_id',
        'title',
        'content',
        'description',
        'status',
        'approved_by',
    ];

    public function pastebin(): BelongsTo
    {
        return $this->belongsTo(Pastebin::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
