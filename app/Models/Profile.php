<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Profile model representing additional user information.
 */
class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string>
     */
    protected $fillable = [
        'bio',      
        'avatar',   
        'user_id'  
    ];

    /**
     * Get the user that owns this profile.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all comments made on this profile.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<Comment>
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}