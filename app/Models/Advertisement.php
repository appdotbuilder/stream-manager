<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Advertisement
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $placement
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Advertisement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Advertisement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Advertisement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Advertisement active()
 * @method static \Illuminate\Database\Eloquent\Builder|Advertisement forPlacement($placement)
 * @method static \Illuminate\Database\Eloquent\Factories\Factory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Advertisement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'placement',
        'is_active',
        'sort_order',
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
     * Scope a query to only include active advertisements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by placement.
     */
    public function scopeForPlacement($query, $placement)
    {
        return $query->where('placement', $placement)->orderBy('sort_order');
    }
}