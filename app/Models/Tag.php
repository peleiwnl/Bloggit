<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Tag model for categorizing and labeling posts.
 */
class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string>
     */
    protected $fillable = [
        'name'  // The unique name of the tag
    ];



    /**
     * Get all posts that have this tag.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Post>
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}