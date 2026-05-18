<?php

namespace App\Enum;

enum Visibility: string
{
    case PRIVATE = 'private';
    case PUBLIC = 'public';

    public function label(): string
    {
        return match($this) {
            self::PRIVATE => 'Private',
            self::PUBLIC => 'Public',
        };
    }
    public function badge(): string
    {
        return match($this) {
            self::PRIVATE => '<span class="inline-flex items-center gap-1 bg-red-600/10 border border-red-600/30 text-red-500 font-black text-[9px] px-2 py-0.5 rounded-sm uppercase tracking-[0.1em]">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Private
                              </span>',
            self::PUBLIC => '<span class="inline-flex items-center gap-1 bg-green-600/10 border border-green-600/30 text-green-500 font-black text-[9px] px-2 py-0.5 rounded-sm uppercase tracking-[0.1em]">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4"/></svg>
                                Public
                             </span>',
        };
    }
    public function color(): string
    {
        return match($this) {
            self::PRIVATE => 'text-gray-500',
            self::PUBLIC => 'text-green-500',
        };
    }

}
