<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Watchlist
 *
 * @property int $id
 * @property int $user_id
 * @property string $watchable_type
 * @property int $watchable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $watchable
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Watchlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Watchlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Watchlist query()
 * @method static \Illuminate\Database\Eloquent\Factories\Factory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Watchlist extends Model
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the watchlist.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent watchable model (movie or series).
     */
    public function watchable(): MorphTo
    {
        return $this->morphTo();
    }
}