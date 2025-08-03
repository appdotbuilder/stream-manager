<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Episode
 *
 * @property int $id
 * @property int $season_id
 * @property string $title
 * @property int $episode_number
 * @property string|null $description
 * @property string|null $thumbnail_url
 * @property int|null $duration
 * @property string|null $video_url
 * @property array|null $video_qualities
 * @property float $rating
 * @property string|null $air_date
 * @property int|null $tmdb_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Season $season
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WatchHistory> $watchHistory
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Episode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Episode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Episode query()
 * @method static \Illuminate\Database\Eloquent\Builder|Episode active()
 * @method static \Illuminate\Database\Eloquent\Factories\Factory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Episode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'season_id',
        'title',
        'episode_number',
        'description',
        'thumbnail_url',
        'duration',
        'video_url',
        'video_qualities',
        'rating',
        'air_date',
        'tmdb_id',
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
        'air_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the season that owns the episode.
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * Get all comments for the episode.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get all watch history for the episode.
     */
    public function watchHistory(): MorphMany
    {
        return $this->morphMany(WatchHistory::class, 'watchable');
    }

    /**
     * Scope a query to only include active episodes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}