<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryItem extends Model
{
    protected $fillable = ['story_id', 'media_url', 'type', 'link_url', 'order', 'caption', 'caption_color'];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
