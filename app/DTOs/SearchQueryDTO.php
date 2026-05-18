<?php

namespace App\DTOs;

use App\Enum\Visibility;
use Illuminate\Http\Request;

class SearchQueryDTO
{
    public function __construct(
        public readonly ?string $query,
        public readonly Visibility $visibility = Visibility::PUBLIC,
        public readonly ?string $author = null,
        public readonly string $sortBy = 'relevance',
        public readonly ?string $dateRange = null,
        public readonly ?int $minLength = null,
        public readonly ?int $maxLength = null,
        public readonly ?int $cursor = null,
        public readonly int $perPage = 10
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            query: $request->input('q') ? trim($request->input('q')) : null,
            visibility: Visibility::PUBLIC, // Strictly enforce PUBLIC search for security/privacy
            author: $request->input('author') ? trim($request->input('author')) : null,
            sortBy: $request->input('sort', 'relevance'),
            dateRange: $request->input('date'),
            minLength: $request->input('min_length') ? (int) $request->input('min_length') : null,
            maxLength: $request->input('max_length') ? (int) $request->input('max_length') : null,
            cursor: $request->input('cursor') ? (int) $request->input('cursor') : null,
            perPage: min((int) $request->input('limit', 10), 50) // Enforce hard cap to protect Tor bandwidth
        );
    }
}
