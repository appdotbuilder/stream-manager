<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Season
 *
 * @property int $id
 * @property int $series_id
 * @property string $title
 * @property int $season_number
 * @property string|null $description
 * @property string|null $poster_url
 * @property int|null $release_year
 * @property int|null $tmdb_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Series $series
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Episode> $episodes
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Season newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season query()
 * @method static \Illuminate\Database\Eloquent\Builder|Season active()
 * @method static \Illuminate\Database\Eloquent\Factories\Factory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Season extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'series_id',
        'title',
        'season_number',
        'description',
        'poster_url',
        'release_year',
        'tmdb_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the series that owns the season.
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get the episodes for the season.
     */
    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    /**
     * Scope a query to only include active seasons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}