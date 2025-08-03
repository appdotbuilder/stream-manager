<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display the streaming platform homepage.
     */
    public function index(Request $request)
    {
        $genres = Genre::all();
        
        // Featured/Hero movie
        $heroMovie = Movie::active()
            ->with('genres')
            ->inRandomOrder()
            ->first();

        // Latest movies
        $latestMovies = Movie::active()
            ->with('genres')
            ->latest()
            ->limit(20)
            ->get();

        // Popular movies
        $popularMovies = Movie::active()
            ->with('genres')
            ->popular()
            ->limit(20)
            ->get();

        // Movies by genres (first 3 genres with movies)
        $moviesByGenres = [];
        $topGenres = Genre::whereHas('movies', function ($query) {
            $query->where('is_active', true);
        })->take(3)->get();

        foreach ($topGenres as $genre) {
            $moviesByGenres[] = [
                'genre' => $genre,
                'movies' => Movie::active()
                    ->with('genres')
                    ->byGenre($genre->id)
                    ->limit(20)
                    ->get()
            ];
        }

        // Continue watching (for authenticated users)
        $continueWatching = [];
        if ($request->user()) {
            $continueWatching = WatchHistory::where('user_id', $request->user()->id)
                ->where('completed', false)
                ->with(['watchable' => function ($morphTo) {
                    $morphTo->morphWith([
                        Movie::class => ['genres'],
                        Episode::class => ['season.series']
                    ]);
                }])
                ->recent()
                ->limit(10)
                ->get();
        }

        // Advertisements
        $heroAds = Advertisement::active()
            ->forPlacement('homepage_hero')
            ->get();
        
        $sidebarAds = Advertisement::active()
            ->forPlacement('homepage_sidebar')
            ->get();

        return Inertia::render('welcome', [
            'heroMovie' => $heroMovie,
            'latestMovies' => $latestMovies,
            'popularMovies' => $popularMovies,
            'moviesByGenres' => $moviesByGenres,
            'continueWatching' => $continueWatching,
            'genres' => $genres,
            'heroAds' => $heroAds,
            'sidebarAds' => $sidebarAds,
        ]);
    }
}