<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Movie
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $poster_url
 * @property string|null $backdrop_url
 * @property int|null $release_year
 * @property float $rating
 * @property int|null $duration
 * @property string|null $video_url
 * @property array|null $video_qualities
 * @property int|null $tmdb_id
 * @property string|null $imdb_id
 * @property string|null $trailer_url
 * @property bool $is_premium
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Genre> $genres
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WatchHistory> $watchHistory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Watchlist> $watchlists
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie active()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie popular()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie byGenre($genreId)
 * @method static \Illuminate\Database\Eloquent\Factories\Factory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'poster_url',
        'backdrop_url',
        'release_year',
        'rating',
        'duration',
        'video_url',
        'video_qualities',
        'tmdb_id',
        'imdb_id',
        'trailer_url',
        'is_premium',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'video_qualities' => 'array',
        'rating' => 'decimal:1',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the genres for the movie.
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    /**
     * Get all comments for the movie.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get all watch history for the movie.
     */
    public function watchHistory(): MorphMany
    {
        return $this->morphMany(WatchHistory::class, 'watchable');
    }

    /**
     * Get all watchlists for the movie.
     */
    public function watchlists(): MorphMany
    {
        return $this->morphMany(Watchlist::class, 'watchable');
    }

    /**
     * Scope a query to only include active movies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by popularity (rating and recent).
     */
    public function scopePopular($query)
    {
        return $query->orderByDesc('rating')->orderByDesc('created_at');
    }

    /**
     * Scope a query to filter by genre.
     */
    public function scopeByGenre($query, $genreId)
    {
        return $query->whereHas('genres', function ($q) use ($genreId) {
            $q->where('genres.id', $genreId);
        });
    }
}