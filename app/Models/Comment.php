<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = ['user_id', 'pastebin_id', 'content'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pastebin(): BelongsTo
    {
        return $this->belongsTo(Pastebin::class);
    }

    /**
     * Get a cleaned version of the comment content without blockquotes for previews.
     */
    public function getCleanContentAttribute(): string
    {
        // 1. Remove blockquote lines (lines starting with '>')
        $cleaned = preg_replace('/^>.*$/m', '', $this->content);
        
        // 2. Collapse whitespace
        $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));

        // 3. Fallback if the comment was ONLY a quote
        if (empty($cleaned)) {
            // Just strip the '>' characters
            $cleaned = preg_replace('/^>\s*/m', '', $this->content);
            $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));
            // Remove typical quote headers
            $cleaned = preg_replace('/\*\*@\w+\*\* said:/i', '', $cleaned);
            $cleaned = trim($cleaned);
        }

        return $cleaned;
    }
}
