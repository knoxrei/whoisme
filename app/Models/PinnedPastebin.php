<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PinnedPastebin extends Model
{
    protected $fillable = [
        'pastebin_id',
        'pinned_by',
        'sort_order',
    ];

    public function pastebin(): BelongsTo
    {
        return $this->belongsTo(Pastebin::class);
    }

    public function pinnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pinned_by');
    }
}
