<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'title', 'thumbnail_url', 'link_url', 'is_active', 'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(StoryItem::class, 'story_id')->orderBy('order');
    }
}
