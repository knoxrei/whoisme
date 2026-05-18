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
    ];

    public function pastebin(): BelongsTo
    {
        return $this->belongsTo(Pastebin::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
