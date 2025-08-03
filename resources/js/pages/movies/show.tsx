import React, { useState, useRef, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import { Play, Pause, Volume2, VolumeX, Maximize, Settings, ArrowLeft, Plus, Check, Star, Clock, Calendar, MessageCircle } from 'lucide-react';

interface Movie {
    id: number;
    title: string;
    description: string;
    poster_url: string;
    backdrop_url: string;
    release_year: number;
    rating: number;
    duration: number;
    video_url: string;
    video_qualities: { [key: string]: string };
    trailer_url: string;
    genres: Array<{ id: number; name: string; }>;
    comments: Array<{
        id: number;
        content: string;
        rating: number;
        created_at: string;
        user: { name: string; };
    }>;
    is_premium: boolean;
}

interface WatchHistory {
    progress: number;
    duration: number;
}

interface Advertisement {
    id: number;
    name: string;
    code: string;
}

interface Props {
    movie: Movie;
    similarMovies: Movie[];
    watchHistory: WatchHistory | null;
    isInWatchlist: boolean;
    topAds: Advertisement[];
    bottomAds: Advertisement[];
    auth?: {
        user: {
            id: number;
            name: string;
            role: string;
        };
    };
    [key: string]: unknown;
}

export default function MovieShow({ 
    movie, 
    similarMovies, 
    watchHistory, 
    isInWatchlist, 
    topAds, 
    bottomAds, 
    auth 
}: Props) {
    const [isPlaying, setIsPlaying] = useState(false);
    const [currentTime, setCurrentTime] = useState(watchHistory?.progress || 0);
    const [duration, setDuration] = useState(0);
    const [volume, setVolume] = useState(1);
    const [isMuted, setIsMuted] = useState(false);
    const [quality, setQuality] = useState('1080p');
    const [showControls, setShowControls] = useState(true);
    const [isFullscreen, setIsFullscreen] = useState(false);
    const [comment, setComment] = useState('');
    const [commentRating, setCommentRating] = useState(5);
    
    const videoRef = useRef<HTMLVideoElement>(null);
    const playerRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (videoRef.current && watchHistory) {
            videoRef.current.currentTime = watchHistory.progress;
        }
    }, [watchHistory]);

    const togglePlay = () => {
        if (videoRef.current) {
            if (isPlaying) {
                videoRef.current.pause();
            } else {
                videoRef.current.play();
            }
            setIsPlaying(!isPlaying);
        }
    };

    const handleTimeUpdate = () => {
        if (videoRef.current) {
            const current = videoRef.current.currentTime;
            setCurrentTime(current);
            
            // Update progress every 10 seconds
            if (Math.floor(current) % 10 === 0) {
                updateWatchProgress(current);
            }
        }
    };

    const handleLoadedMetadata = () => {
        if (videoRef.current) {
            setDuration(videoRef.current.duration);
        }
    };

    const updateWatchProgress = (progress: number) => {
        if (auth?.user) {
            router.post('/movies', {
                movie_id: movie.id,
                action: 'update_progress',
                progress: Math.floor(progress),
                duration: Math.floor(duration)
            }, {
                preserveState: true,
                preserveScroll: true
            });
        }
    };

    const toggleWatchlist = () => {
        if (!auth?.user) {
            router.get('/login');
            return;
        }

        router.post('/movies', {
            movie_id: movie.id,
            action: isInWatchlist ? 'remove_from_watchlist' : 'add_to_watchlist'
        });
    };

    const formatTime = (seconds: number) => {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        
        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    };

    const submitComment = (e: React.FormEvent) => {
        e.preventDefault();
        if (!auth?.user) {
            router.get('/login');
            return;
        }

        router.post(`/movies/${movie.id}/comments`, {
            content: comment,
            rating: commentRating
        }, {
            onSuccess: () => {
                setComment('');
                setCommentRating(5);
            }
        });
    };

    const MovieCard = ({ movie: similarMovie }: { movie: Movie }) => (
        <Link href={`/movies/${similarMovie.id}`} className="flex-shrink-0 w-48 group">
            <div className="relative overflow-hidden rounded-lg bg-gray-900 transition-transform group-hover:scale-105">
                <img
                    src={similarMovie.poster_url || '/images/placeholder-movie.jpg'}
                    alt={similarMovie.title}
                    className="w-full h-72 object-cover"
                />
                <div className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                    <Play className="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                </div>
                {similarMovie.is_premium && (
                    <Badge className="absolute top-2 right-2 bg-yellow-600">
                        ⭐ Premium
                    </Badge>
                )}
            </div>
            <div className="mt-2 px-1">
                <h3 className="font-semibold text-sm truncate text-white">{similarMovie.title}</h3>
                <div className="flex items-center gap-2 text-xs text-gray-400 mt-1">
                    <span className="flex items-center gap-1">
                        <Star className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                        {similarMovie.rating}
                    </span>
                    <span>{similarMovie.release_year}</span>
                </div>
            </div>
        </Link>
    );

    return (
        <div className="min-h-screen bg-black">
            <Head title={`${movie.title} - StreamFlix`} />

            {/* Header */}
            <header className="absolute top-0 left-0 right-0 z-50 bg-black bg-opacity-50 backdrop-blur-sm">
                <div className="container mx-auto px-4 py-4">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-4">
                            <Link href="/">
                                <Button variant="ghost" size="sm" className="text-white">
                                    <ArrowLeft className="w-4 h-4 mr-2" />
                                    Back
                                </Button>
                            </Link>
                            <Link href="/" className="text-red-600 text-xl font-bold">
                                🎬 STREAMFLIX
                            </Link>
                        </div>
                        {auth?.user && (
                            <div className="text-white text-sm">
                                Welcome, {auth.user.name}
                            </div>
                        )}
                    </div>
                </div>
            </header>

            {/* Top Ads */}
            {topAds.length > 0 && (
                <div className="pt-20 bg-black">
                    <div className="container mx-auto px-4 py-4">
                        {topAds.map(ad => (
                            <div key={ad.id} dangerouslySetInnerHTML={{ __html: ad.code }} />
                        ))}
                    </div>
                </div>
            )}

            {/* Video Player */}
            <div 
                ref={playerRef}
                className="relative bg-black"
                onMouseEnter={() => setShowControls(true)}
                onMouseLeave={() => setShowControls(false)}
            >
                <video
                    ref={videoRef}
                    className="w-full aspect-video"
                    src={movie.video_qualities?.[quality] || movie.video_url}
                    poster={movie.backdrop_url || movie.poster_url}
                    onTimeUpdate={handleTimeUpdate}
                    onLoadedMetadata={handleLoadedMetadata}
                    onPlay={() => setIsPlaying(true)}
                    onPause={() => setIsPlaying(false)}
                />

                {/* Video Controls */}
                <div className={`absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent transition-opacity duration-300 ${showControls ? 'opacity-100' : 'opacity-0'}`}>
                    {/* Play/Pause Overlay */}
                    <div className="absolute inset-0 flex items-center justify-center">
                        <Button
                            onClick={togglePlay}
                            className="bg-black bg-opacity-50 hover:bg-opacity-70 text-white rounded-full p-4"
                        >
                            {isPlaying ? (
                                <Pause className="w-8 h-8" />
                            ) : (
                                <Play className="w-8 h-8" />
                            )}
                        </Button>
                    </div>

                    {/* Bottom Controls */}
                    <div className="absolute bottom-0 left-0 right-0 p-4">
                        {/* Progress Bar */}
                        <div className="mb-4">
                            <div className="bg-gray-600 h-1 rounded-full">
                                <div 
                                    className="bg-red-600 h-full rounded-full transition-all duration-300"
                                    style={{ width: `${(currentTime / duration) * 100}%` }}
                                />
                            </div>
                        </div>

                        {/* Control Buttons */}
                        <div className="flex items-center justify-between">
                            <div className="flex items-center gap-4">
                                <Button
                                    onClick={togglePlay}
                                    variant="ghost"
                                    size="sm"
                                    className="text-white"
                                >
                                    {isPlaying ? <Pause className="w-5 h-5" /> : <Play className="w-5 h-5" />}
                                </Button>

                                <div className="flex items-center gap-2">
                                    <Button
                                        onClick={() => setIsMuted(!isMuted)}
                                        variant="ghost"
                                        size="sm"
                                        className="text-white"
                                    >
                                        {isMuted || volume === 0 ? <VolumeX className="w-5 h-5" /> : <Volume2 className="w-5 h-5" />}
                                    </Button>
                                    <input
                                        type="range"
                                        min="0"
                                        max="1"
                                        step="0.1"
                                        value={isMuted ? 0 : volume}
                                        onChange={(e) => setVolume(parseFloat(e.target.value))}
                                        className="w-20"
                                    />
                                </div>

                                <span className="text-white text-sm">
                                    {formatTime(currentTime)} / {formatTime(duration)}
                                </span>
                            </div>

                            <div className="flex items-center gap-2">
                                {/* Quality Selector */}
                                {movie.video_qualities && (
                                    <select
                                        value={quality}
                                        onChange={(e) => setQuality(e.target.value)}
                                        className="bg-black bg-opacity-50 text-white rounded px-2 py-1 text-sm"
                                    >
                                        {Object.keys(movie.video_qualities).map(q => (
                                            <option key={q} value={q}>{q}</option>
                                        ))}
                                    </select>
                                )}

                                <Button
                                    variant="ghost"
                                    size="sm"
                                    className="text-white"
                                >
                                    <Settings className="w-5 h-5" />
                                </Button>

                                <Button
                                    onClick={() => setIsFullscreen(!isFullscreen)}
                                    variant="ghost"
                                    size="sm"
                                    className="text-white"
                                >
                                    <Maximize className="w-5 h-5" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Player Overlay Ads */}
                {topAds.length > 0 && showControls && (
                    <div className="absolute top-4 right-4">
                        {topAds.slice(0, 1).map(ad => (
                            <div key={ad.id} dangerouslySetInnerHTML={{ __html: ad.code }} />
                        ))}
                    </div>
                )}
            </div>

            {/* Movie Information */}
            <div className="container mx-auto px-4 py-8">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div className="lg:col-span-2">
                        {/* Movie Details */}
                        <div className="mb-8">
                            <div className="flex items-start gap-6 mb-6">
                                <img
                                    src={movie.poster_url || '/images/placeholder-movie.jpg'}
                                    alt={movie.title}
                                    className="w-48 h-72 object-cover rounded-lg"
                                />
                                <div className="flex-1">
                                    <h1 className="text-4xl font-bold text-white mb-4">{movie.title}</h1>
                                    
                                    <div className="flex items-center gap-4 mb-4">
                                        <div className="flex items-center gap-2 text-white">
                                            <Star className="w-5 h-5 fill-yellow-400 text-yellow-400" />
                                            <span className="font-semibold">{movie.rating}</span>
                                        </div>
                                        <div className="flex items-center gap-2 text-gray-300">
                                            <Calendar className="w-4 h-4" />
                                            <span>{movie.release_year}</span>
                                        </div>
                                        <div className="flex items-center gap-2 text-gray-300">
                                            <Clock className="w-4 h-4" />
                                            <span>{movie.duration} min</span>
                                        </div>
                                        {movie.is_premium && (
                                            <Badge className="bg-yellow-600">⭐ Premium</Badge>
                                        )}
                                    </div>

                                    <div className="flex flex-wrap gap-2 mb-6">
                                        {movie.genres.map(genre => (
                                            <Badge key={genre.id} variant="secondary">
                                                {genre.name}
                                            </Badge>
                                        ))}
                                    </div>

                                    <p className="text-gray-300 mb-6 leading-relaxed">
                                        {movie.description}
                                    </p>

                                    <div className="flex gap-4">
                                        <Button
                                            onClick={toggleWatchlist}
                                            variant="outline"
                                            className="border-gray-400 text-white hover:bg-white hover:text-black"
                                        >
                                            {isInWatchlist ? (
                                                <>
                                                    <Check className="w-4 h-4 mr-2" />
                                                    In Watchlist
                                                </>
                                            ) : (
                                                <>
                                                    <Plus className="w-4 h-4 mr-2" />
                                                    Add to Watchlist
                                                </>
                                            )}
                                        </Button>

                                        {movie.trailer_url && (
                                            <Button variant="outline">
                                                <Play className="w-4 h-4 mr-2" />
                                                Watch Trailer
                                            </Button>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Comments Section */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                                <MessageCircle className="w-6 h-6" />
                                Reviews & Comments
                            </h2>

                            {/* Add Comment Form */}
                            {auth?.user ? (
                                <Card className="bg-gray-900 border-gray-700 mb-6">
                                    <CardContent className="p-6">
                                        <form onSubmit={submitComment}>
                                            <div className="mb-4">
                                                <label className="block text-white text-sm font-medium mb-2">
                                                    Your Rating
                                                </label>
                                                <div className="flex gap-1">
                                                    {[1, 2, 3, 4, 5].map(rating => (
                                                        <button
                                                            key={rating}
                                                            type="button"
                                                            onClick={() => setCommentRating(rating)}
                                                            className={`text-2xl transition-colors ${
                                                                rating <= commentRating 
                                                                    ? 'text-yellow-400' 
                                                                    : 'text-gray-600'
                                                            }`}
                                                        >
                                                            ⭐
                                                        </button>
                                                    ))}
                                                </div>
                                            </div>
                                            <Textarea
                                                value={comment}
                                                onChange={(e) => setComment(e.target.value)}
                                                placeholder="Share your thoughts about this movie..."
                                                className="mb-4 bg-gray-800 border-gray-600 text-white"
                                                rows={4}
                                            />
                                            <Button type="submit" disabled={!comment.trim()}>
                                                Post Review
                                            </Button>
                                        </form>
                                    </CardContent>
                                </Card>
                            ) : (
                                <Card className="bg-gray-900 border-gray-700 mb-6">
                                    <CardContent className="p-6 text-center">
                                        <p className="text-gray-300 mb-4">
                                            Sign in to leave a review and rating
                                        </p>
                                        <Link href="/login">
                                            <Button>Sign In</Button>
                                        </Link>
                                    </CardContent>
                                </Card>
                            )}

                            {/* Comments List */}
                            <div className="space-y-4">
                                {movie.comments.map(comment => (
                                    <Card key={comment.id} className="bg-gray-900 border-gray-700">
                                        <CardContent className="p-6">
                                            <div className="flex items-start justify-between mb-3">
                                                <div>
                                                    <h4 className="font-semibold text-white">{comment.user.name}</h4>
                                                    <div className="flex items-center gap-2 mt-1">
                                                        <div className="flex">
                                                            {Array.from({ length: 5 }).map((_, i) => (
                                                                <Star
                                                                    key={i}
                                                                    className={`w-4 h-4 ${
                                                                        i < comment.rating 
                                                                            ? 'fill-yellow-400 text-yellow-400' 
                                                                            : 'text-gray-600'
                                                                    }`}
                                                                />
                                                            ))}
                                                        </div>
                                                        <span className="text-sm text-gray-400">
                                                            {new Date(comment.created_at).toLocaleDateString()}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p className="text-gray-300 leading-relaxed">{comment.content}</p>
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Sidebar */}
                    <div className="lg:col-span-1">
                        {/* Similar Movies */}
                        <div className="mb-8">
                            <h3 className="text-xl font-bold text-white mb-4">More Like This</h3>
                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                                {similarMovies.map(similarMovie => (
                                    <MovieCard key={similarMovie.id} movie={similarMovie} />
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Bottom Ads */}
            {bottomAds.length > 0 && (
                <div className="bg-gray-900 py-8">
                    <div className="container mx-auto px-4">
                        {bottomAds.map(ad => (
                            <div key={ad.id} dangerouslySetInnerHTML={{ __html: ad.code }} />
                        ))}
                    </div>
                </div>
            )}
        </div>
    );
}