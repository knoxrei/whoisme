<?php

namespace App\Enum;

enum Role: string
{
    case OWNER = 'owner';
    case MODERATOR = 'moderator';
    case MEMBER = 'member';
    case ADVERTISER = 'advertiser';
    case PRIME = 'prime';
    case BANNED = 'banned';
    case VIP = 'vip';
    case RICH = 'rich';

    /**
     * Get the display label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::OWNER => 'Owner',
            self::MODERATOR => 'Moderator',
            self::MEMBER => 'Member',
            self::ADVERTISER => 'Advertiser',
            self::PRIME => 'Prime',
            self::BANNED => 'Banned',
            self::VIP => 'VIP',
            self::RICH => 'Rich',
        };
    }
    public function preview(): string
    {
        return match ($this) {
            self::VIP => "<div class='vipUser'><span>Anonymous</span> [VIP]</div>",
            self::PRIME => "<div class='primeUser'><span>Anonymous</span> [PRIME]</div>",
            self::RICH => "<div class='richUser'><span>Anonymous</span> [RICH]</div>",
            default => "<span>Anonymous</span>",
        };
    }

public function userStyle(string $username)
{
    return match ($this) {
        self::VIP => "<span class='vipUser'>{$username}</span>",
        self::PRIME => "<span class='primeUser'>{$username}</span>",
        self::RICH => "<span class='richUser'>{$username}</span>",
        self::OWNER => "<span class='ownerUser'>{$username}</span>",
        self::MODERATOR => "<span class='moderatorUser'>{$username}</span>",
        self::BANNED => "<span class='bannedUser'>{$username}</span>",
        self::MEMBER => "<span class='noobUser'>{$username}</span>",
        default => "<span>{$username}</span>",
    };
}

public function userStyleWithBanner(string $username, string $color): string
{

    return match ($this) {
        self::VIP => "<div><span class='vipUser' style='color: {$color};'>{$username}</span> <span style='color: " . $this->color() . ";'>[VIP]</span></div>",

        self::PRIME => "<div><span class='primeUser' style='color: {$color};'>{$username}</span> <span style='color: " . $this->color() . ";'>[PRIME]</span></div>",

        self::RICH => "<div><span class='richUser' style='color: {$color};'>{$username}</span> <span style='color: " . $this->color() . ";'>[RICH]</span></div>",

        self::OWNER => "<div><span class='ownerUser' style='color: {$color};'>{$username}</span> <span style='color: " . $this->color() . ";'>[OWNER]</span></div>",

        self::MODERATOR => "<div><span class='moderatorUser' style='color: {$color};'>{$username}</span> <span style='color: " . $this->color() . ";'>[MODERATOR]</span></div>",

        self::BANNED => "<div><span class='bannedUser' style='color: {$color};'>{$username}</span> <span style='color: " . $this->color() . ";'>[BANNED]</span></div>",

        self::MEMBER => "<div><span class='noobUser'>{$username}</span> <span style='color: " . $this->color() . ";'>[MEMBER]</span></div>",

        default => "<span>{$username}</span>",
    };
}
// for banner in show profile

    /**
     * Get the purchase price for the role.
     */
    public function price(): int
    {
        return match ($this) {
            self::VIP => 5,
            self::PRIME => 15,
            self::RICH => 25,
            default => 0,
        };
    }


    /**
     * Get the color associated with the role.
     */

    public function color(): string
    {
        return match ($this) {
            self::VIP => '#0c0', // Light Purple
            self::PRIME => '#4B0082', // Dark Purple (Indigo)
            self::RICH => '#FFD700', // Sparkling Gold
            self::OWNER => '#FF4500', // Orange Red for Owner
            self::MODERATOR => '#1E90FF', // Dodger Blue for Mod
            default => '#808080',
        };
    }

    /**
     * Get the paste highlight color for the role.
     */

    /**
     * Determine if the role allows .GIF profile pictures.
     */
    public function canHaveGifAvatar(): bool
    {
        return match ($this) {
            self::VIP, self::PRIME, self::RICH, self::OWNER, self::MODERATOR => true,
            default => false,
        };
    }

   public function canPriorityOrderUser():bool {
    return match($this){
         self::RICH, self::OWNER,self::PRIME, self::MODERATOR => true,
        default => false,
    };
   }

    /**
     * Determine if the role can private their own pastes.
     */
    public function canPrivatePastes(): bool
    {
        return match ($this) {
            self::RICH, self::OWNER,self::PRIME, self::MODERATOR => true,
            default => false,
        };
    }

    /**
     * Determine if the role can password protect their pastes.
     */
    public function canPasswordProtect(): bool
    {
        return match ($this) {
            self::PRIME, self::RICH, self::OWNER, self::MODERATOR => true,
            default => false,
        };
    }

    /**
     * Get the number of allowed username changes for the role.
     */
    public function allowedUsernameChanges(): int
    {
        return match ($this) {
            self::VIP => 1,
            self::PRIME => 2,
            self::RICH => 3,
            self::OWNER, self::MODERATOR => 99,
            default => 0,
        };
    }
}


