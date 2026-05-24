<?php

namespace App\Helper;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VisitorTracker
{
    /**
     * How many seconds a visitor remains "active".
     */
    protected static int $ttl = 180; // 3 minutes

    /**
     * Get the anonymous visitor name stored in session.
     */
    public static function getAnonName(): string
    {
        return session('anonuser', 'Anonymous');
    }

    /**
     * Build a visitor record for the current request.
     */
    public static function buildVisitorRecord(): array
    {
        $user = auth()->user();

        if ($user) {
            return [
                'type'       => 'member',
                'identifier' => $user->id,
                'name'       => $user->username,
                'role'       => $user->identification?->role?->value ?? 'member',
                'role_label' => $user->identification?->role?->label() ?? 'Member',
                'role_color' => $user->identification?->role?->color() ?? '#808080',
                'last_seen'  => now()->timestamp,
            ];
        }

        return [
            'type'       => 'guest',
            'identifier' => session()->getId(),
            'name'       => self::getAnonName(),
            'role'       => null,
            'role_label' => 'Guest',
            'role_color' => '#4b5563',
            'last_seen'  => now()->timestamp,
        ];
    }

    /**
     * Track a visitor on a specific pastebin page.
     */
    public static function trackPastebin(string $slug): void
    {
        $cacheKey = "visitors:pastebin:{$slug}";
        self::upsertVisitor($cacheKey);
    }

    /**
     * Track a visitor on the root page.
     */
    public static function trackRoot(): void
    {
        $cacheKey = 'visitors:root';
        self::upsertVisitor($cacheKey);
    }

    /**
     * Get all active visitors for a pastebin page.
     */
    public static function getPastebinVisitors(string $slug): array
    {
        $cacheKey = "visitors:pastebin:{$slug}";
        return self::getActiveVisitors($cacheKey);
    }

    /**
     * Get all active visitors for the root page.
     */
    public static function getRootVisitors(): array
    {
        return self::getActiveVisitors('visitors:root');
    }

    /**
     * Upsert the current visitor into the cache list.
     */
    protected static function upsertVisitor(string $cacheKey): void
    {
        $visitor    = self::buildVisitorRecord();
        $identifier = $visitor['identifier'];

        // Lock to prevent race condition
        $visitors = Cache::get($cacheKey, []);

        // Update or add visitor
        $visitors[$identifier] = $visitor;

        // Purge expired visitors while we're here
        $cutoff = now()->timestamp - self::$ttl;
        $visitors = array_filter($visitors, fn($v) => $v['last_seen'] >= $cutoff);

        Cache::put($cacheKey, $visitors, self::$ttl + 30);
    }

    /**
     * Get all non-expired visitors from a cache key.
     */
    protected static function getActiveVisitors(string $cacheKey): array
    {
        $visitors = Cache::get($cacheKey, []);
        $cutoff   = now()->timestamp - self::$ttl;

        return array_values(
            array_filter($visitors, fn($v) => $v['last_seen'] >= $cutoff)
        );
    }

    /**
     * Count visitors for a pastebin.
     */
    public static function countPastebinVisitors(string $slug): int
    {
        return count(self::getPastebinVisitors($slug));
    }

    /**
     * Count visitors on root page.
     */
    public static function countRootVisitors(): int
    {
        return count(self::getRootVisitors());
    }
}
