<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = [
        'name', 'slug', 'type', 'is_active', 'description', 
        'predictor_prize_1', 'predictor_prize_2', 'predictor_prize_3'
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function matches()
    {
        return $this->hasMany(MatchModel::class);
    }
}
