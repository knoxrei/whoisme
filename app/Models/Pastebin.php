<?php

namespace App\Models;

use App\Enum\Visibility;
use App\Trait\SlugGenerateModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pastebin extends Model
{
    use SlugGenerateModel;
    protected $fillable = [
        'user_id',
        'author_name',
        'slug',
        'title',
        'content',
        'views_count',
        'password',
        'cover_path',
        'download_count',
        'description',
        'visibility',
        'is_self_destruct',
    ];
    protected $casts = [
        'password' => 'hashed',
        'visibility' => Visibility::class,
        'is_self_destruct' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function edits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PastebinEdit::class);
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ImagePastebin::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function pinnedRecord(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PinnedPastebin::class);
    }
}
