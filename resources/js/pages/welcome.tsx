import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Play, Plus, Search, Star, Clock, Calendar } from 'lucide-react';

interface Movie {
    id: number;
    title: string;
    description: string;
    poster_url: string;
    backdrop_url: string;
    release_year: number;
    rating: number;
    duration: number;
    genres: Array<{ id: number; name: string; }>;
    is_premium: boolean;
}

interface Genre {
    id: number;
    name: string;
}

interface WatchHistoryItem {
    id: number;
    progress: number;
    duration: number;
    watchable: Movie;
}

interface Advertisement {
    id: number;
    name: string;
    code: string;
}

interface Props {
    heroMovie?: Movie;
    latestMovies: Movie[];
    popularMovies: Movie[];
    moviesByGenres: Array<{
        genre: Genre;
        movies: Movie[];
    }>;
    continueWatching: WatchHistoryItem[];
    genres: Genre[];
    heroAds: Advertisement[];
    sidebarAds: Advertisement[];
    auth?: {
        user: {
            id: number;
            name: string;
            role: string;
        };
    };
    [key: string]: unknown;
}

export default function Welcome({ 
    heroMovie, 
    latestMovies, 
    popularMovies, 
    moviesByGenres, 
    continueWatching, 
    // genres, // Available but not used in this component
    heroAds,
    sidebarAds,
    auth 
}: Props) {
    const [searchQuery, setSearchQuery] = useState('');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        if (searchQuery.trim()) {
            router.get('/search', { q: searchQuery });
        }
    };

    const addToWatchlist = (movieId: number) => {
        if (!auth?.user) {
            router.get('/login');
            return;
        }

        router.post('/movies', {
            movie_id: movieId,
            action: 'add_to_watchlist'
        });
    };

    const MovieCard = ({ movie }: { movie: Movie }) => (
        <div className="flex-shrink-0 w-48 group cursor-pointer">
            <div className="relative overflow-hidden rounded-lg bg-gray-900 transition-transform group-hover:scale-105">
                <img
                    src={movie.poster_url || '/images/placeholder-movie.jpg'}
                    alt={movie.title}
                    className="w-full h-72 object-cover"
                />
                <div className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                    <div className="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex gap-2">
                        <Link href={`/movies/${movie.id}`}>
                            <Button size="sm" className="bg-white text-black hover:bg-gray-200">
                                <Play className="w-4 h-4 mr-1" />
                                Play
                            </Button>
                        </Link>
                        <Button 
                            size="sm" 
                            variant="outline" 
                            className="border-white text-white hover:bg-white hover:text-black"
                            onClick={() => addToWatchlist(movie.id)}
                        >
                            <Plus className="w-4 h-4" />
                        </Button>
                    </div>
                </div>
                {movie.is_premium && (
                    <Badge className="absolute top-2 right-2 bg-yellow-600">
                        ⭐ Premium
                    </Badge>
                )}
            </div>
            <div className="mt-2 px-1">
                <h3 className="font-semibold text-sm truncate text-white">{movie.title}</h3>
                <div className="flex items-center gap-2 text-xs text-gray-400 mt-1">
                    <span className="flex items-center gap-1">
                        <Star className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                        {movie.rating}
                    </span>
                    <span>{movie.release_year}</span>
                    {movie.duration && (
                        <span className="flex items-center gap-1">
                            <Clock className="w-3 h-3" />
                            {movie.duration}m
                        </span>
                    )}
                </div>
                <div className="flex flex-wrap gap-1 mt-1">
                    {movie.genres.slice(0, 2).map(genre => (
                        <Badge key={genre.id} variant="secondary" className="text-xs">
                            {genre.name}
                        </Badge>
                    ))}
                </div>
            </div>
        </div>
    );

    const MovieRow = ({ title, movies }: { title: string; movies: Movie[] }) => (
        <div className="mb-8">
            <h2 className="text-xl font-bold text-white mb-4">{title}</h2>
            <div className="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                {movies.map(movie => (
                    <MovieCard key={movie.id} movie={movie} />
                ))}
            </div>
        </div>
    );

    return (
        <div className="min-h-screen bg-black">
            <Head title="🎬 NetFlix Clone - Stream Movies & TV Shows" />

            {/* Header */}
            <header className="relative z-50 bg-black bg-opacity-90 backdrop-blur-sm">
                <div className="container mx-auto px-4 py-4">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-8">
                            <Link href="/" className="text-red-600 text-2xl font-bold">
                                🎬 STREAMFLIX
                            </Link>
                            <nav className="hidden md:flex items-center gap-6">
                                <Link href="/" className="text-white hover:text-gray-300">Home</Link>
                                <Link href="/movies" className="text-white hover:text-gray-300">Movies</Link>
                                <Link href="/series" className="text-white hover:text-gray-300">TV Shows</Link>
                                <Link href="/search" className="text-white hover:text-gray-300">Browse</Link>
                            </nav>
                        </div>

                        <div className="flex items-center gap-4">
                            <form onSubmit={handleSearch} className="hidden md:flex items-center">
                                <div className="relative">
                                    <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                                    <Input
                                        type="text"
                                        placeholder="Search movies, TV shows..."
                                        value={searchQuery}
                                        onChange={(e) => setSearchQuery(e.target.value)}
                                        className="pl-10 bg-gray-800 border-gray-700 text-white placeholder-gray-400 w-64"
                                    />
                                </div>
                            </form>

                            {auth?.user ? (
                                <div className="flex items-center gap-3">
                                    <Link href="/profile" className="text-white hover:text-gray-300">
                                        {auth.user.name}
                                    </Link>
                                    <Link href="/logout" method="post" as="button" className="text-gray-400 hover:text-white">
                                        Logout
                                    </Link>
                                </div>
                            ) : (
                                <div className="flex gap-2">
                                    <Link href="/login">
                                        <Button variant="outline" size="sm">
                                            Sign In
                                        </Button>
                                    </Link>
                                    <Link href="/register">
                                        <Button size="sm" className="bg-red-600 hover:bg-red-700">
                                            Get Started
                                        </Button>
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </header>

            {/* Hero Section */}
            {heroMovie && (
                <section className="relative h-screen">
                    <div className="absolute inset-0">
                        <img
                            src={heroMovie.backdrop_url || heroMovie.poster_url || '/images/placeholder-backdrop.jpg'}
                            alt={heroMovie.title}
                            className="w-full h-full object-cover"
                        />
                        <div className="absolute inset-0 bg-gradient-to-r from-black via-black/50 to-transparent" />
                        <div className="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent" />
                    </div>

                    <div className="relative z-10 container mx-auto px-4 h-full flex items-center">
                        <div className="max-w-2xl">
                            <h1 className="text-5xl md:text-7xl font-bold text-white mb-4">
                                {heroMovie.title}
                            </h1>
                            <p className="text-lg text-gray-300 mb-6 line-clamp-3">
                                {heroMovie.description}
                            </p>
                            <div className="flex items-center gap-4 mb-8">
                                <div className="flex items-center gap-2 text-white">
                                    <Star className="w-5 h-5 fill-yellow-400 text-yellow-400" />
                                    <span className="font-semibold">{heroMovie.rating}</span>
                                </div>
                                <div className="flex items-center gap-2 text-gray-300">
                                    <Calendar className="w-4 h-4" />
                                    <span>{heroMovie.release_year}</span>
                                </div>
                                {heroMovie.duration && (
                                    <div className="flex items-center gap-2 text-gray-300">
                                        <Clock className="w-4 h-4" />
                                        <span>{heroMovie.duration} min</span>
                                    </div>
                                )}
                            </div>
                            <div className="flex flex-wrap gap-2 mb-8">
                                {heroMovie.genres.map(genre => (
                                    <Badge key={genre.id} variant="secondary">
                                        {genre.name}
                                    </Badge>
                                ))}
                            </div>
                            <div className="flex gap-4">
                                <Link href={`/movies/${heroMovie.id}`}>
                                    <Button size="lg" className="bg-white text-black hover:bg-gray-200">
                                        <Play className="w-5 h-5 mr-2" />
                                        ▶️ Watch Now
                                    </Button>
                                </Link>
                                <Button
                                    size="lg"
                                    variant="outline"
                                    className="border-gray-400 text-white hover:bg-white hover:text-black"
                                    onClick={() => addToWatchlist(heroMovie.id)}
                                >
                                    <Plus className="w-5 h-5 mr-2" />
                                    My List
                                </Button>
                            </div>
                        </div>
                    </div>

                    {/* Hero Ads */}
                    {heroAds.length > 0 && (
                        <div className="absolute top-20 right-4 z-20">
                            {heroAds.map(ad => (
                                <div key={ad.id} dangerouslySetInnerHTML={{ __html: ad.code }} />
                            ))}
                        </div>
                    )}
                </section>
            )}

            {/* Content Sections */}
            <main className="relative z-10 bg-black">
                <div className="container mx-auto px-4 py-8">
                    <div className="flex gap-8">
                        {/* Main Content */}
                        <div className="flex-1">
                            {/* Continue Watching */}
                            {continueWatching && continueWatching.length > 0 && (
                                <div className="mb-8">
                                    <h2 className="text-xl font-bold text-white mb-4">
                                        📺 Continue Watching
                                    </h2>
                                    <div className="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                                        {continueWatching.map(item => (
                                            <div key={item.id} className="flex-shrink-0 w-48">
                                                <div className="relative">
                                                    <MovieCard movie={item.watchable} />
                                                    <div className="absolute bottom-16 left-0 right-0 bg-red-600 h-1">
                                                        <div 
                                                            className="bg-red-400 h-full" 
                                                            style={{ width: `${(item.progress / item.duration) * 100}%` }}
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Latest Movies */}
                            {latestMovies.length > 0 && (
                                <MovieRow title="🆕 Latest Releases" movies={latestMovies} />
                            )}

                            {/* Popular Movies */}
                            {popularMovies.length > 0 && (
                                <MovieRow title="🔥 Trending Now" movies={popularMovies} />
                            )}

                            {/* Movies by Genre */}
                            {moviesByGenres.map(({ genre, movies }) => (
                                <MovieRow 
                                    key={genre.id} 
                                    title={`🎭 ${genre.name}`} 
                                    movies={movies} 
                                />
                            ))}

                            {/* Welcome Section for New Users */}
                            {!auth?.user && (
                                <div className="mt-16 text-center">
                                    <Card className="bg-gray-900 border-gray-800 max-w-2xl mx-auto">
                                        <CardContent className="p-8">
                                            <h2 className="text-3xl font-bold text-white mb-4">
                                                🎬 Welcome to StreamFlix
                                            </h2>
                                            <p className="text-gray-300 mb-6">
                                                Discover thousands of movies and TV shows. Stream in HD quality 
                                                with premium features like resume watching, personalized recommendations, 
                                                and ad-free viewing.
                                            </p>
                                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                                <Link href="/register">
                                                    <Button size="lg" className="bg-red-600 hover:bg-red-700 w-full sm:w-auto">
                                                        🚀 Start Free Trial
                                                    </Button>
                                                </Link>
                                                <Link href="/login">
                                                    <Button size="lg" variant="outline" className="w-full sm:w-auto">
                                                        Sign In
                                                    </Button>
                                                </Link>
                                            </div>
                                        </CardContent>
                                    </Card>
                                </div>
                            )}
                        </div>

                        {/* Sidebar Ads */}
                        {sidebarAds.length > 0 && (
                            <div className="w-64 flex-shrink-0 hidden lg:block">
                                <div className="sticky top-20 space-y-4">
                                    {sidebarAds.map(ad => (
                                        <div key={ad.id} dangerouslySetInnerHTML={{ __html: ad.code }} />
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </main>

            {/* Footer */}
            <footer className="bg-gray-900 text-gray-400 py-12">
                <div className="container mx-auto px-4">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <h3 className="text-white font-semibold mb-4">StreamFlix</h3>
                            <p className="text-sm">
                                Your premium destination for movies and TV shows. 
                                Stream anywhere, anytime.
                            </p>
                        </div>
                        <div>
                            <h4 className="text-white font-semibold mb-4">Browse</h4>
                            <ul className="space-y-2 text-sm">
                                <li><Link href="/movies" className="hover:text-white">Movies</Link></li>
                                <li><Link href="/series" className="hover:text-white">TV Shows</Link></li>
                                <li><Link href="/search" className="hover:text-white">Search</Link></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="text-white font-semibold mb-4">Account</h4>
                            <ul className="space-y-2 text-sm">
                                <li><Link href="/profile" className="hover:text-white">My Profile</Link></li>
                                <li><Link href="/watchlist" className="hover:text-white">Watchlist</Link></li>
                                <li><Link href="/history" className="hover:text-white">Watch History</Link></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="text-white font-semibold mb-4">Support</h4>
                            <ul className="space-y-2 text-sm">
                                <li><Link href="/help" className="hover:text-white">Help Center</Link></li>
                                <li><Link href="/contact" className="hover:text-white">Contact Us</Link></li>
                                <li><Link href="/terms" className="hover:text-white">Terms of Service</Link></li>
                            </ul>
                        </div>
                    </div>
                    <div className="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                        <p>&copy; 2024 StreamFlix. All rights reserved. 🎬</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}