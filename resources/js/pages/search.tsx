import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Search as SearchIcon, Filter, Grid, List, Star, Clock, Calendar, Play } from 'lucide-react';

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

interface Series {
    id: number;
    title: string;
    description: string;
    poster_url: string;
    backdrop_url: string;
    release_year: number;
    rating: number;
    is_premium: boolean;
}

interface Genre {
    id: number;
    name: string;
}

interface Props {
    query: string;
    movies: Movie[];
    series: Series[];
    genres: Genre[];
    filters: {
        genre: string;
        year: string;
        rating: string;
        type: string;
    };
    totalResults: number;
    auth?: {
        user: {
            id: number;
            name: string;
            role: string;
        };
    };
    [key: string]: unknown;
}

export default function Search({ 
    query, 
    movies, 
    series, 
    genres, 
    filters, 
    totalResults, 
    auth 
}: Props) {
    const [searchQuery, setSearchQuery] = useState(query);
    const [selectedGenre, setSelectedGenre] = useState(filters.genre || '');
    const [selectedYear, setSelectedYear] = useState(filters.year || '');
    const [selectedRating, setSelectedRating] = useState(filters.rating || '');
    const [selectedType, setSelectedType] = useState(filters.type || 'all');
    const [viewMode, setViewMode] = useState<'grid' | 'list'>('grid');
    const [showFilters, setShowFilters] = useState(false);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        performSearch();
    };

    const performSearch = () => {
        const params: Record<string, string> = {};
        if (searchQuery) params.q = searchQuery;
        if (selectedGenre) params.genre = selectedGenre;
        if (selectedYear) params.year = selectedYear;
        if (selectedRating) params.rating = selectedRating;
        if (selectedType !== 'all') params.type = selectedType;

        router.get('/search', params);
    };

    const clearFilters = () => {
        setSelectedGenre('');
        setSelectedYear('');
        setSelectedRating('');
        setSelectedType('all');
        router.get('/search', { q: searchQuery });
    };

    const currentYear = new Date().getFullYear();
    const years = Array.from({ length: 50 }, (_, i) => currentYear - i);
    const ratings = [1, 2, 3, 4, 5];

    const MovieCard = ({ movie, isListView = false }: { movie: Movie; isListView?: boolean }) => (
        <Link href={`/movies/${movie.id}`}>
            <Card className={`bg-gray-900 border-gray-700 hover:bg-gray-800 transition-all group ${isListView ? 'flex' : ''}`}>
                <div className={`relative overflow-hidden ${isListView ? 'w-32 h-48 flex-shrink-0' : 'aspect-[2/3]'}`}>
                    <img
                        src={movie.poster_url || '/images/placeholder-movie.jpg'}
                        alt={movie.title}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                    <div className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <Play className="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                    </div>
                    {movie.is_premium && (
                        <Badge className="absolute top-2 right-2 bg-yellow-600 text-xs">
                            ⭐ Premium
                        </Badge>
                    )}
                </div>
                <CardContent className={`p-4 ${isListView ? 'flex-1' : ''}`}>
                    <h3 className="font-semibold text-white mb-2 line-clamp-1">{movie.title}</h3>
                    {isListView && (
                        <p className="text-gray-400 text-sm mb-3 line-clamp-2">{movie.description}</p>
                    )}
                    <div className="flex items-center gap-2 text-xs text-gray-400 mb-2">
                        <span className="flex items-center gap-1">
                            <Star className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                            {movie.rating}
                        </span>
                        <span className="flex items-center gap-1">
                            <Calendar className="w-3 h-3" />
                            {movie.release_year}
                        </span>
                        {movie.duration && (
                            <span className="flex items-center gap-1">
                                <Clock className="w-3 h-3" />
                                {movie.duration}m
                            </span>
                        )}
                    </div>
                    <div className="flex flex-wrap gap-1">
                        {movie.genres.slice(0, isListView ? 4 : 2).map(genre => (
                            <Badge key={genre.id} variant="secondary" className="text-xs">
                                {genre.name}
                            </Badge>
                        ))}
                    </div>
                </CardContent>
            </Card>
        </Link>
    );

    const SeriesCard = ({ series, isListView = false }: { series: Series; isListView?: boolean }) => (
        <Link href={`/series/${series.id}`}>
            <Card className={`bg-gray-900 border-gray-700 hover:bg-gray-800 transition-all group ${isListView ? 'flex' : ''}`}>
                <div className={`relative overflow-hidden ${isListView ? 'w-32 h-48 flex-shrink-0' : 'aspect-[2/3]'}`}>
                    <img
                        src={series.poster_url || '/images/placeholder-movie.jpg'}
                        alt={series.title}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                    <div className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                        <Play className="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                    </div>
                    {series.is_premium && (
                        <Badge className="absolute top-2 right-2 bg-yellow-600 text-xs">
                            ⭐ Premium
                        </Badge>
                    )}
                    <Badge className="absolute top-2 left-2 bg-red-600 text-xs">
                        📺 Series
                    </Badge>
                </div>
                <CardContent className={`p-4 ${isListView ? 'flex-1' : ''}`}>
                    <h3 className="font-semibold text-white mb-2 line-clamp-1">{series.title}</h3>
                    {isListView && (
                        <p className="text-gray-400 text-sm mb-3 line-clamp-2">{series.description}</p>
                    )}
                    <div className="flex items-center gap-2 text-xs text-gray-400 mb-2">
                        <span className="flex items-center gap-1">
                            <Star className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                            {series.rating}
                        </span>
                        <span className="flex items-center gap-1">
                            <Calendar className="w-3 h-3" />
                            {series.release_year}
                        </span>
                    </div>
                </CardContent>
            </Card>
        </Link>
    );

    return (
        <div className="min-h-screen bg-black">
            <Head title={query ? `Search: ${query} - StreamFlix` : 'Search - StreamFlix'} />

            {/* Header */}
            <header className="bg-black bg-opacity-90 backdrop-blur-sm border-b border-gray-800">
                <div className="container mx-auto px-4 py-4">
                    <div className="flex items-center justify-between">
                        <Link href="/" className="text-red-600 text-2xl font-bold">
                            🎬 STREAMFLIX
                        </Link>
                        
                        <nav className="hidden md:flex items-center gap-6">
                            <Link href="/" className="text-white hover:text-gray-300">Home</Link>
                            <Link href="/movies" className="text-white hover:text-gray-300">Movies</Link>
                            <Link href="/series" className="text-white hover:text-gray-300">TV Shows</Link>
                            <Link href="/search" className="text-red-400 font-semibold">Search</Link>
                        </nav>

                        {auth?.user ? (
                            <div className="flex items-center gap-3 text-white">
                                <span>Welcome, {auth.user.name}</span>
                                <Link href="/logout" method="post" as="button" className="text-gray-400 hover:text-white">
                                    Logout
                                </Link>
                            </div>
                        ) : (
                            <div className="flex gap-2">
                                <Link href="/login">
                                    <Button variant="outline" size="sm">Sign In</Button>
                                </Link>
                                <Link href="/register">
                                    <Button size="sm" className="bg-red-600 hover:bg-red-700">Join Now</Button>
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </header>

            <div className="container mx-auto px-4 py-8">
                {/* Search Header */}
                <div className="mb-8">
                    <div className="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between mb-6">
                        <div>
                            <h1 className="text-3xl font-bold text-white mb-2">
                                {query ? `Search Results for "${query}"` : '🔍 Search Movies & TV Shows'}
                            </h1>
                            {totalResults > 0 && (
                                <p className="text-gray-400">
                                    Found {totalResults} result{totalResults !== 1 ? 's' : ''}
                                </p>
                            )}
                        </div>

                        <div className="flex items-center gap-2">
                            <Button
                                variant={viewMode === 'grid' ? 'default' : 'outline'}
                                size="sm"
                                onClick={() => setViewMode('grid')}
                            >
                                <Grid className="w-4 h-4" />
                            </Button>
                            <Button
                                variant={viewMode === 'list' ? 'default' : 'outline'}
                                size="sm"
                                onClick={() => setViewMode('list')}
                            >
                                <List className="w-4 h-4" />
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                onClick={() => setShowFilters(!showFilters)}
                            >
                                <Filter className="w-4 h-4 mr-2" />
                                Filters
                            </Button>
                        </div>
                    </div>

                    {/* Search Form */}
                    <form onSubmit={handleSearch} className="mb-6">
                        <div className="relative max-w-2xl">
                            <SearchIcon className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
                            <Input
                                type="text"
                                placeholder="Search for movies, TV shows, actors, directors..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="pl-12 pr-4 py-3 bg-gray-900 border-gray-700 text-white placeholder-gray-400 text-lg"
                            />
                            <Button 
                                type="submit" 
                                className="absolute right-2 top-1/2 transform -translate-y-1/2"
                            >
                                Search
                            </Button>
                        </div>
                    </form>

                    {/* Filters */}
                    {showFilters && (
                        <Card className="bg-gray-900 border-gray-700 mb-6">
                            <CardContent className="p-6">
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                                    <div>
                                        <label className="block text-white text-sm font-medium mb-2">Type</label>
                                        <select
                                            value={selectedType}
                                            onChange={(e) => setSelectedType(e.target.value)}
                                            className="w-full bg-gray-800 border-gray-600 text-white rounded-md px-3 py-2"
                                        >
                                            <option value="all">All</option>
                                            <option value="movies">Movies</option>
                                            <option value="series">TV Shows</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label className="block text-white text-sm font-medium mb-2">Genre</label>
                                        <select
                                            value={selectedGenre}
                                            onChange={(e) => setSelectedGenre(e.target.value)}
                                            className="w-full bg-gray-800 border-gray-600 text-white rounded-md px-3 py-2"
                                        >
                                            <option value="">All Genres</option>
                                            {genres.map(genre => (
                                                <option key={genre.id} value={genre.id}>
                                                    {genre.name}
                                                </option>
                                            ))}
                                        </select>
                                    </div>

                                    <div>
                                        <label className="block text-white text-sm font-medium mb-2">Year</label>
                                        <select
                                            value={selectedYear}
                                            onChange={(e) => setSelectedYear(e.target.value)}
                                            className="w-full bg-gray-800 border-gray-600 text-white rounded-md px-3 py-2"
                                        >
                                            <option value="">All Years</option>
                                            {years.map(year => (
                                                <option key={year} value={year}>
                                                    {year}
                                                </option>
                                            ))}
                                        </select>
                                    </div>

                                    <div>
                                        <label className="block text-white text-sm font-medium mb-2">Min Rating</label>
                                        <select
                                            value={selectedRating}
                                            onChange={(e) => setSelectedRating(e.target.value)}
                                            className="w-full bg-gray-800 border-gray-600 text-white rounded-md px-3 py-2"
                                        >
                                            <option value="">Any Rating</option>
                                            {ratings.map(rating => (
                                                <option key={rating} value={rating}>
                                                    {rating}+ Stars
                                                </option>
                                            ))}
                                        </select>
                                    </div>

                                    <div className="flex items-end gap-2">
                                        <Button onClick={performSearch} className="flex-1">
                                            Apply Filters
                                        </Button>
                                        <Button onClick={clearFilters} variant="outline">
                                            Clear
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    )}
                </div>

                {/* Results */}
                {totalResults === 0 && query ? (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">🔍</div>
                        <h2 className="text-2xl font-bold text-white mb-4">No Results Found</h2>
                        <p className="text-gray-400 mb-6">
                            We couldn't find anything matching "{query}". Try different keywords or browse our categories.
                        </p>
                        <div className="flex justify-center gap-4">
                            <Link href="/movies">
                                <Button>Browse Movies</Button>
                            </Link>
                            <Link href="/series">
                                <Button variant="outline">Browse TV Shows</Button>
                            </Link>
                        </div>
                    </div>
                ) : (
                    <>
                        {/* Movies */}
                        {movies.length > 0 && (
                            <div className="mb-12">
                                <h2 className="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                                    🎬 Movies ({movies.length})
                                </h2>
                                <div className={viewMode === 'grid' 
                                    ? "grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4"
                                    : "space-y-4"
                                }>
                                    {movies.map(movie => (
                                        <MovieCard key={movie.id} movie={movie} isListView={viewMode === 'list'} />
                                    ))}
                                </div>
                            </div>
                        )}

                        {/* Series */}
                        {series.length > 0 && (
                            <div className="mb-12">
                                <h2 className="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                                    📺 TV Shows ({series.length})
                                </h2>
                                <div className={viewMode === 'grid' 
                                    ? "grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4"
                                    : "space-y-4"
                                }>
                                    {series.map(seriesItem => (
                                        <SeriesCard key={seriesItem.id} series={seriesItem} isListView={viewMode === 'list'} />
                                    ))}
                                </div>
                            </div>
                        )}
                    </>
                )}

                {/* Popular Searches */}
                {!query && (
                    <div className="mt-12">
                        <h2 className="text-2xl font-bold text-white mb-6">🔥 Popular Searches</h2>
                        <div className="flex flex-wrap gap-3">
                            {['Action', 'Comedy', 'Drama', 'Horror', 'Sci-Fi', 'Romance', 'Thriller', 'Documentary'].map(term => (
                                <Button
                                    key={term}
                                    variant="outline"
                                    onClick={() => {
                                        setSearchQuery(term);
                                        router.get('/search', { q: term });
                                    }}
                                    className="border-gray-600 text-gray-300 hover:bg-gray-800"
                                >
                                    {term}
                                </Button>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}