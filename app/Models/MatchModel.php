<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchModel extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'home_team_id', 'away_team_id', 'group_id', 'stage', 'matchday',
        'home_score', 'away_score', 'match_date', 'venue', 'broadcaster_logo', 'status',
        'home_possession', 'away_possession', 'home_shots', 'away_shots',
        'home_corners', 'away_corners', 'home_offsides', 'away_offsides',
        'home_fouls', 'away_fouls', 'home_free_kicks', 'away_free_kicks', 'home_throw_ins', 'away_throw_ins',
        'home_saves', 'away_saves', 'home_goal_kicks', 'away_goal_kicks',
        'home_scorers', 'away_scorers', 'report', 'motm_player_id',
        'referee', 'referee_ar1', 'referee_ar2', 'referee_id', 'referee_ar1_id', 'referee_ar2_id', 'attendance', 'highlights_url', 'highlights_thumbnail'
    ];

    protected $casts = [
        'match_date' => 'datetime',
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class, 'match_id')->orderBy('minute', 'asc');
    }

    public function motmPlayer()
    {
        return $this->belongsTo(Player::class, 'motm_player_id');
    }

    public function lineups()
    {
        return $this->belongsToMany(Player::class, 'match_lineups', 'match_id', 'player_id')
                    ->withPivot('team_id', 'is_captain', 'is_substitute', 'shirt_number', 'position_key', 'position_x', 'position_y')
                    ->withTimestamps();
    }

    public function commentaries()
    {
        return $this->hasMany(MatchCommentary::class, 'match_id')->orderBy('created_at', 'desc');
    }

    public function assignedReferee()
    {
        return $this->belongsTo(Referee::class, 'referee_id');
    }

    public function assignedAssistant1()
    {
        return $this->belongsTo(Referee::class, 'referee_ar1_id');
    }

    public function assignedAssistant2()
    {
        return $this->belongsTo(Referee::class, 'referee_ar2_id');
    }
}
