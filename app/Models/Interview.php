<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'interviewee_name',
        'interviewee_role',
        'is_featured',
        'display_order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
