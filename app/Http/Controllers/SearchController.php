<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Series;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    /**
     * Handle search requests.
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $genre = $request->get('genre');
        $year = $request->get('year');
        $rating = $request->get('rating');
        $type = $request->get('type', 'all'); // all, movies, series

        $movies = collect();
        $series = collect();

        if ($query || $genre || $year || $rating) {
            // Search movies
            if ($type === 'all' || $type === 'movies') {
                $movieQuery = Movie::active()->with('genres');

                if ($query) {
                    $movieQuery->where('title', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                }

                if ($genre) {
                    $movieQuery->byGenre($genre);
                }

                if ($year) {
                    $movieQuery->where('release_year', $year);
                }

                if ($rating) {
                    $movieQuery->where('rating', '>=', $rating);
                }

                $movies = $movieQuery->limit(50)->get();
            }

            // Search series
            if ($type === 'all' || $type === 'series') {
                $seriesQuery = Series::active();

                if ($query) {
                    $seriesQuery->where('title', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                }

                if ($year) {
                    $seriesQuery->where('release_year', $year);
                }

                if ($rating) {
                    $seriesQuery->where('rating', '>=', $rating);
                }

                $series = $seriesQuery->limit(50)->get();
            }
        }

        $genres = Genre::all();

        return Inertia::render('search', [
            'query' => $query,
            'movies' => $movies,
            'series' => $series,
            'genres' => $genres,
            'filters' => [
                'genre' => $genre,
                'year' => $year,
                'rating' => $rating,
                'type' => $type,
            ],
            'totalResults' => $movies->count() + $series->count(),
        ]);
    }
}