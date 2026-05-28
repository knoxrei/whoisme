<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportPastebin extends Model
{
    protected $fillable = [
        'pastebin_id',
        'reason',
        'user_id',
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
