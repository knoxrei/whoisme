<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'email', 'password', 'last_active'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_active' => 'datetime',
        ];
    }

    /**
     * Relationship to user identification (roles, bio, etc.)
     */
    public function identification(): HasOne
    {
        return $this->hasOne(Identification::class);
    }

    /**
     * Relationship to user's pastebins
     */
    public function pastebins(): HasMany
    {
        return $this->hasMany(Pastebin::class);
    }

    /**
     * Users who follow this user
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * Users whom this user follows
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Determine if the user can use premium features like password protection and private visibility.
     */
    public function canUsePremiumFeatures(): bool
    {
        return $this->identification?->role?->canPasswordProtect();
    }

    public function edits(): HasMany
    {
        return $this->hasMany(PastebinEdit::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
