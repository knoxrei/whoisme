<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkMailCampaign extends Model
{
    protected $fillable = [
        'created_by',
        'subject',
        'message',
        'verified_only',
        'total_recipients',
        'sent_count',
        'skipped_count',
        'processed_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'verified_only' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isFinished(): bool
    {
        return in_array($this->status, ['completed', 'failed'], true);
    }

    public function progressPercent(): int
    {
        if ($this->total_recipients === 0) {
            return 0;
        }

        return (int) min(100, round(($this->processed_count / $this->total_recipients) * 100));
    }
}
