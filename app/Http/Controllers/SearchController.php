<?php

namespace App\Http\Controllers;

use App\DTOs\SearchQueryDTO;
use App\Models\Pastebin;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected SearchService $searchService
    ) {}

    /**
     * Handles search index homepage and search results rendering.
     */
    public function index(Request $request)
    {
        $query = $request->input('q');

        // Render sleek search landing page if no search term provided
        if ($query === null || trim($query) === '') {
            $title   = 'Doxme Search';
            $trending = $this->searchService->getTrendingPastes(5);
            $stats   = $this->searchService->getSiteStats();

            return view('search.index', compact('title', 'trending', 'stats'));
        }

        $dto      = SearchQueryDTO::fromRequest($request);
        $response = $this->searchService->execute($dto);

        $results       = $response['results'];
        $nextCursor    = $response['next_cursor'];
        $count         = $response['count'];
        $executionTime = $response['execution_time_ms'];

        $title = htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . ' - Paste Search';

        // AJAX Load More: return partial rows JSON
        if ($request->ajax() || $request->input('ajax')) {
            $html = view('search.partials.result-rows', compact('results'))->render();
            return response()->json([
                'html'        => $html,
                'next_cursor' => $nextCursor,
                'count'       => $count,
            ]);
        }

        return view('search.results', compact(
            'results', 'nextCursor', 'count', 'executionTime', 'title', 'dto'
        ));
    }

    /**
     * Live Autocomplete Endpoint — returns JSON array of title suggestions.
     * Extremely fast: title-only FULLTEXT, cached 60s, max 8 results.
     */
    public function suggest(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $suggestions = $this->searchService->suggest($q);

        return response()->json(
            array_map(fn ($s) => [
                'title'       => $s['title'],
                'slug'        => $s['slug'],
                'author_name' => $s['author_name'],
            ], $suggestions)
        );
    }

    /**
     * Site Stats Endpoint — returns live indexed paste counts.
     * Used by homepage live counter. Cached 5 min.
     */
    public function stats(Request $request)
    {
        return response()->json($this->searchService->getSiteStats());
    }

    /**
     * Advanced Search Options Page.
     */
    public function advanced(Request $request)
    {
        $title = 'Advanced Search Parameters';
        return view('search.advanced', compact('title'));
    }

    /**
     * Trending Pastes Page.
     */
    public function trending(Request $request)
    {
        $title  = 'Trending Paste Index';
        $pastes = $this->searchService->getTrendingPastes(25);

        return view('search.trending', compact('title', 'pastes'));
    }

    /**
     * Recent Pastes Page — cursor-based feed with AJAX Load More support.
     */
    public function recent(Request $request)
    {
        $title  = 'Realtime Public Pastes';
        $cursor = $request->input('cursor');

        $query = Pastebin::query()
            ->with(['user.identification'])
            ->where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false);

        if ($cursor) {
            $query->where('id', '<', $cursor);
        }

        $pastes = $query->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc') // stable secondary sort
            ->limit(20)
            ->get();

        $nextCursor = null;
        if ($pastes->count() === 20) {
            $nextCursor = $pastes->last()->id;
        }

        if ($request->ajax() || $request->input('ajax')) {
            $html = view('search.partials.recent-rows', compact('pastes'))->render();
            return response()->json([
                'html'        => $html,
                'next_cursor' => $nextCursor,
            ]);
        }

        return view('search.recent', compact('title', 'pastes', 'nextCursor'));
    }
}
