<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagePastebin extends Model
{
    protected $fillable = [
        'image_path',
        'pastebin_id',
    ];

    public function pastebin(): BelongsTo
    {
        return $this->belongsTo(Pastebin::class);
    }
}
