<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read mixed $path
 * @property-read Folder|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Folder> $subfolders
 * @property-read int|null $subfolders_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Folder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Folder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Folder query()
 * @mixin \Eloquent
 */
class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the folder
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent folder
     */
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    /**
     * Get the subfolders
     */
    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    /**
     * Get all files in this folder
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get the full path of the folder
     */
    public function getPathAttribute()
    {
        $path = collect([$this->name]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }

        return $path->implode(' / ');
    }
}
