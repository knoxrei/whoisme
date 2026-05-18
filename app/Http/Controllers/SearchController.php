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

        // Render sleek search landing page (Google style) if no search term provided
        if ($query === null || trim($query) === '') {
            $title = 'Doxme Search';
            
            // Get quick facts or trending tags for homepage links
            $trending = $this->searchService->getTrendingPastes(5);

            return view('search.index', compact('title', 'trending'));
        }

        // Parse search request filters into a structured DTO
        $dto = SearchQueryDTO::fromRequest($request);
        
        // Execute the service orchestrator
        $response = $this->searchService->execute($dto);

        $results = $response['results'];
        $nextCursor = $response['next_cursor'];
        $count = $response['count'];
        $executionTime = $response['execution_time_ms'];

        $title = htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . ' - Paste Search';

        return view('search.results', compact(
            'results',
            'nextCursor',
            'count',
            'executionTime',
            'title',
            'dto'
        ));
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
        $title = 'Trending Paste Index';
        $pastes = $this->searchService->getTrendingPastes(25);

        return view('search.trending', compact('title', 'pastes'));
    }

    /**
     * Recent Pastes Page.
     */
    public function recent(Request $request)
    {
        $title = 'Realtime Public Pastes';
        
        // Simple cursor pagination for recent posts to keep it extremely fast
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
            ->limit(20)
            ->get();

        $nextCursor = null;
        if ($pastes->count() === 20) {
            $nextCursor = $pastes->last()->id;
        }

        return view('search.recent', compact('title', 'pastes', 'nextCursor'));
    }
}
