<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'team_id', 'position', 'shirt_number', 'image_url', 'full_image_url'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function goals()
    {
        return $this->hasMany(MatchEvent::class, 'player_id')->where('event_type', 'goal');
    }

    public function assists()
    {
        return $this->hasMany(MatchEvent::class, 'assist_player_id')->where('event_type', 'goal');
    }

    public function cards()
    {
        return $this->hasMany(MatchEvent::class, 'player_id')->whereIn('event_type', ['yellow_card', 'red_card']);
    }

    public function yellowCards()
    {
        return $this->hasMany(MatchEvent::class, 'player_id')->where('event_type', 'yellow_card');
    }

    public function redCards()
    {
        return $this->hasMany(MatchEvent::class, 'player_id')->where('event_type', 'red_card');
    }

    public function motmAwards()
    {
        return $this->hasMany(MatchModel::class, 'motm_player_id');
    }

    public function matchLineups()
    {
        return $this->belongsToMany(MatchModel::class, 'match_lineups', 'player_id', 'match_id');
    }

    public function getSuspensionStatusAttribute()
    {
        return app(\App\Services\SuspensionService::class)->getSuspensionStatus($this);
    }

    public function getIsSuspendedAttribute()
    {
        return $this->suspension_status['is_suspended'];
    }
}
