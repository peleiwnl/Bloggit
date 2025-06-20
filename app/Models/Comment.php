<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Comment model representing user comments across different entities.
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string>
     */
    protected $fillable = [
        'body',              
        'user_id',          
        'commentable_id',    
        'commentable_type',  
        'is_edited',        
        'parent_id'         
    ];
    
    /**
     * The attributes that should be cast.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'is_edited' => 'boolean',
    ];


    /**
     * Get the user who wrote the comment.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent model that was commented on.
     * 
     * This could be a Post or Profile.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Get all replies to this comment.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Comment>
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    
    /**
     * Get the parent comment if this is a reply.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Comment>
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}