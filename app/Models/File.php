<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \App\Models\Folder|null $folder
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|File active()
 * @method static \Illuminate\Database\Eloquent\Builder|File favourites()
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File trashed()
 * @mixin \Eloquent
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'folder_id',
        'catalog_id',
        'name',
        'filename',
        'path',
        'size',
        'mime_type',
        'is_favourite',
        'deleted_at',
    ];

    protected $casts = [
        'is_favourite' => 'boolean',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the file
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the folder that contains the file
     */

    /**
     * Get the catalog that this file belongs to
     */
    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    /**
     * Scope a query to only include non-deleted files
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to only include deleted files
     */
    public function scopeTrashed($query)
    {
        return $query->whereNotNull('deleted_at');
    }

    /**
     * Scope a query to only include favourite files
     */
    public function scopeFavourites($query)
    {
        return $query->where('is_favourite', true);
    }
}
