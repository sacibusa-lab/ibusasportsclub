<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_url',
        'nationality',
        'has_fifa_badge',
    ];

    protected $casts = [
        'has_fifa_badge' => 'boolean',
    ];

    public function matches()
    {
        return $this->hasMany(MatchModel::class, 'referee_id');
    }
}
