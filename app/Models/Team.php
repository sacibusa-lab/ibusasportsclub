<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'short_name', 'manager', 'stadium_name', 'primary_color', 'logo_url', 'group_id', 'played', 'wins', 'draws', 'losses', 
        'goals_for', 'goals_against', 'points'
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getGoalDifferenceAttribute()
    {
        return $this->goals_for - $this->goals_against;
    }

    public function homeMatches()
    {
        return $this->hasMany(MatchModel::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(MatchModel::class, 'away_team_id');
    }
}
