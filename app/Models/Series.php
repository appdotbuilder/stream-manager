<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Series
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $poster_url
 * @property string|null $backdrop_url
 * @property int|null $release_year
 * @property float $rating
 * @property int|null $tmdb_id
 * @property bool $is_premium
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Season> $seasons
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Watchlist> $watchlists
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Series newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Series query()
 * @method static \Illuminate\Database\Eloquent\Builder|Series active()
 * @method static \Illuminate\Database\Eloquent\Factories\Factory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Series extends Model
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
        'tmdb_id',
        'is_premium',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'decimal:1',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the seasons for the series.
     */
    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    /**
     * Get all watchlists for the series.
     */
    public function watchlists(): MorphMany
    {
        return $this->morphMany(Watchlist::class, 'watchable');
    }

    /**
     * Scope a query to only include active series.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}