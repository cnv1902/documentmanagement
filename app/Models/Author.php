<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'contact',
    ];

    public function files()
    {
        return $this->belongsToMany(File::class, 'author_file')->withTimestamps();
    }
}