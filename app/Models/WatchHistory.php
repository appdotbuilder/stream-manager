<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\WatchHistory
 *
 * @property int $id
 * @property int $user_id
 * @property string $watchable_type
 * @property int $watchable_id
 * @property int $progress
 * @property int|null $duration
 * @property bool $completed
 * @property \Illuminate\Support\Carbon $last_watched_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $watchable
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|WatchHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WatchHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WatchHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|WatchHistory recent()
 * @method static \Illuminate\Database\Eloquent\Factories\Factory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class WatchHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'watchable_type',
        'watchable_id',
        'progress',
        'duration',
        'completed',
        'last_watched_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed' => 'boolean',
        'last_watched_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the watch history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent watchable model (movie or episode).
     */
    public function watchable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to order by most recent.
     */
    public function scopeRecent($query)
    {
        return $query->orderByDesc('last_watched_at');
    }
}