<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    protected $fillable = [
        'match_id', 
        'team_id', 
        'player_id', 
        'assist_player_id', 
        'related_player_id', 
        'player_name', 
        'player_image_url', 
        'event_type', 
        'minute'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function relatedPlayer()
    {
        return $this->belongsTo(Player::class, 'related_player_id');
    }

    public function assistant()
    {
        return $this->belongsTo(Player::class, 'assist_player_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }
}
