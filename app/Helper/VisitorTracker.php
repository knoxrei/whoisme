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
    protected static int $maxGuestDetails = 80;
    protected const OVERLOAD_VISITOR_KEY = '__guest_overload__';

    /**
     * Get the anonymous visitor name stored in session.
     */
    public static function getAnonName(): string
    {
        $request = request();

        return (string) (
            $request->attributes->get('anon_name')
            ?? $request->cookie('anon_name')
            ?? 'Anonymous'
        );
    }

    /**
     * Build a visitor record for the current request.
     */
    public static function buildVisitorRecord(): array
    {
        $user = auth()->user();
        $now = now()->timestamp;

        if ($user) {
            return [
                'type'       => 'member',
                'identifier' => $user->id,
                'name'       => $user->username,
                'role'       => $user->identification?->role?->value ?? 'member',
                'role_label' => $user->identification?->role?->label() ?? 'Member',
                'role_color' => $user->identification?->role?->color() ?? '#808080',
                'last_seen'  => $now,
            ];
        }

        $request = request();
        $anonTrackerId = (string) (
            $request->attributes->get('anon_tracker_id')
            ?? $request->cookie('anon_tracker_id')
            ?? ''
        );

        if ($anonTrackerId === '') {
            // Fallback for clients that block cookies entirely.
            $anonTrackerId = sha1($request->ip() . '|' . (string) $request->userAgent());
        }

        return [
            'type'       => 'guest',
            'identifier' => 'guest:' . $anonTrackerId,
            'name'       => self::getAnonName(),
            'role'       => null,
            'role_label' => 'Guest',
            'role_color' => '#4b5563',
            'last_seen'  => $now,
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
        $visitor = self::buildVisitorRecord();
        $identifier = $visitor['identifier'];
        $cutoff = now()->timestamp - self::$ttl;
        $visitors = Cache::get($cacheKey, []);
        $visitors = array_filter($visitors, fn($v) => ($v['last_seen'] ?? 0) >= $cutoff);

        if ($visitor['type'] === 'guest' && !isset($visitors[$identifier])) {
            $guestCount = self::countGuestVisitors($visitors);

            if ($guestCount >= self::$maxGuestDetails) {
                self::incrementGuestOverflowCount($cacheKey);
                $visitors[self::OVERLOAD_VISITOR_KEY] = self::buildGuestOverloadVisitor(
                    self::getGuestOverflowCount($cacheKey)
                );

                Cache::put($cacheKey, $visitors, self::$ttl + 30);
                return;
            }
        }

        $visitors[$identifier] = $visitor;

        if (isset($visitors[self::OVERLOAD_VISITOR_KEY])) {
            $visitors[self::OVERLOAD_VISITOR_KEY] = self::buildGuestOverloadVisitor(
                self::getGuestOverflowCount($cacheKey)
            );
        }

        Cache::put($cacheKey, $visitors, self::$ttl + 30);
    }

    /**
     * Get all non-expired visitors from a cache key.
     */
    protected static function getActiveVisitors(string $cacheKey): array
    {
        $visitors = Cache::get($cacheKey, []);
        $cutoff   = now()->timestamp - self::$ttl;
        $overflowCount = self::getGuestOverflowCount($cacheKey);

        $activeVisitors = array_values(
            array_filter($visitors, fn($v) => ($v['last_seen'] ?? 0) >= $cutoff)
        );

        if ($overflowCount > 0) {
            $activeVisitors = array_values(
                array_filter($activeVisitors, fn($v) => ($v['identifier'] ?? null) !== self::OVERLOAD_VISITOR_KEY)
            );
            $activeVisitors[] = self::buildGuestOverloadVisitor($overflowCount);
        }

        return $activeVisitors;
    }

    public static function getPastebinVisitorSnapshot(string $slug): array
    {
        $cacheKey = "visitors:pastebin:{$slug}";
        return self::getVisitorSnapshot($cacheKey);
    }

    public static function getRootVisitorSnapshot(): array
    {
        return self::getVisitorSnapshot('visitors:root');
    }

    protected static function getVisitorSnapshot(string $cacheKey): array
    {
        $visitors = self::getActiveVisitors($cacheKey);
        $overflowCount = self::getGuestOverflowCount($cacheKey);

        $displayCount = count(array_filter(
            $visitors,
            fn($visitor) => ($visitor['identifier'] ?? null) !== self::OVERLOAD_VISITOR_KEY
        ));

        return [
            'visitors' => $visitors,
            'count' => $displayCount + $overflowCount,
            'is_overloaded' => $overflowCount > 0,
        ];
    }

    protected static function countGuestVisitors(array $visitors): int
    {
        return count(array_filter($visitors, function ($visitor) {
            return ($visitor['type'] ?? null) === 'guest'
                && ($visitor['identifier'] ?? null) !== self::OVERLOAD_VISITOR_KEY;
        }));
    }

    protected static function overflowCounterKey(string $cacheKey): string
    {
        return "visitors:overflow:{$cacheKey}";
    }

    protected static function incrementGuestOverflowCount(string $cacheKey): void
    {
        $counterKey = self::overflowCounterKey($cacheKey);
        Cache::increment($counterKey);
        Cache::put($counterKey, Cache::get($counterKey, 0), self::$ttl + 30);
    }

    protected static function getGuestOverflowCount(string $cacheKey): int
    {
        return (int) Cache::get(self::overflowCounterKey($cacheKey), 0);
    }

    protected static function buildGuestOverloadVisitor(int $overflowCount): array
    {
        return [
            'type' => 'guest_overload',
            'identifier' => self::OVERLOAD_VISITOR_KEY,
            'name' => "Anonymous (+{$overflowCount})",
            'role' => null,
            'role_label' => 'Guest',
            'role_color' => '#4b5563',
            'last_seen' => now()->timestamp,
        ];
    }

    /**
     * Count visitors for a pastebin.
     */
    public static function countPastebinVisitors(string $slug): int
    {
        return self::getPastebinVisitorSnapshot($slug)['count'];
    }

    /**
     * Count visitors on root page.
     */
    public static function countRootVisitors(): int
    {
        return self::getRootVisitorSnapshot()['count'];
    }
}
