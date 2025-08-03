<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Comment;
use App\Models\Movie;
use App\Models\WatchHistory;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    /**
     * Display a listing of movies.
     */
    public function index(Request $request)
    {
        $query = Movie::active()->with('genres');

        // Search functionality
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->byGenre($request->genre);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('release_year', $request->year);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // Sort options
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->popular();
                break;
            case 'rating':
                $query->orderByDesc('rating');
                break;
            case 'title':
                $query->orderBy('title');
                break;
            case 'year':
                $query->orderByDesc('release_year');
                break;
            default:
                $query->latest();
        }

        $movies = $query->paginate(24);

        return Inertia::render('movies/index', [
            'movies' => $movies,
            'filters' => $request->only(['search', 'genre', 'year', 'rating', 'sort']),
        ]);
    }

    /**
     * Display the specified movie.
     */
    public function show(Movie $movie)
    {
        $movie->load(['genres', 'comments.user']);

        // Similar movies
        $similarMovies = Movie::active()
            ->with('genres')
            ->where('id', '!=', $movie->id)
            ->whereHas('genres', function ($query) use ($movie) {
                $query->whereIn('genres.id', $movie->genres->pluck('id'));
            })
            ->limit(12)
            ->get();

        // Watch history for authenticated user
        $watchHistory = null;
        $isInWatchlist = false;
        
        if (auth()->check()) {
            $watchHistory = WatchHistory::where('user_id', auth()->id())
                ->where('watchable_type', Movie::class)
                ->where('watchable_id', $movie->id)
                ->first();

            $isInWatchlist = Watchlist::where('user_id', auth()->id())
                ->where('watchable_type', Movie::class)
                ->where('watchable_id', $movie->id)
                ->exists();
        }

        // Advertisements
        $topAds = Advertisement::active()
            ->forPlacement('movie_detail_top')
            ->get();
        
        $bottomAds = Advertisement::active()
            ->forPlacement('movie_detail_bottom')
            ->get();

        return Inertia::render('movies/show', [
            'movie' => $movie,
            'similarMovies' => $similarMovies,
            'watchHistory' => $watchHistory,
            'isInWatchlist' => $isInWatchlist,
            'topAds' => $topAds,
            'bottomAds' => $bottomAds,
        ]);
    }

    /**
     * Add movie to watchlist.
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'action' => 'required|in:add_to_watchlist,remove_from_watchlist,update_progress'
        ]);

        $movie = Movie::findOrFail($request->movie_id);

        if ($request->action === 'add_to_watchlist') {
            Watchlist::firstOrCreate([
                'user_id' => auth()->id(),
                'watchable_type' => Movie::class,
                'watchable_id' => $movie->id,
            ]);

            return back()->with('success', 'Added to watchlist');
        }

        if ($request->action === 'remove_from_watchlist') {
            Watchlist::where('user_id', auth()->id())
                ->where('watchable_type', Movie::class)
                ->where('watchable_id', $movie->id)
                ->delete();

            return back()->with('success', 'Removed from watchlist');
        }

        if ($request->action === 'update_progress') {
            $request->validate([
                'progress' => 'required|integer|min:0',
                'duration' => 'required|integer|min:1',
            ]);

            WatchHistory::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'watchable_type' => Movie::class,
                    'watchable_id' => $movie->id,
                ],
                [
                    'progress' => $request->progress,
                    'duration' => $request->duration,
                    'completed' => $request->progress >= ($request->duration * 0.9), // 90% completion
                    'last_watched_at' => now(),
                ]
            );

            return back()->with('success', 'Progress updated');
        }
    }
}