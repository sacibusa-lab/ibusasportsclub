<?php

namespace App\Services;

use App\Models\Player;
use App\Models\MatchEvent;
use App\Models\MatchModel;

class SuspensionService
{
    /**
     * Check if a player is suspended for a given match or next match.
     */
    public function getSuspensionStatus(Player $player, $competitionId = null)
    {
        // 1. Get all competitive matches for the player's team in this competition
        $teamId = $player->team_id;
        $matches = MatchModel::where(function($q) use ($teamId) {
                $q->where('home_team_id', $teamId)
                  ->orWhere('away_team_id', $teamId);
            })
            ->where('status', 'finished')
            ->where('stage', '!=', 'novelty')
            ->when($competitionId, function($q) use ($competitionId) {
                return $q->where('competition_id', $competitionId);
            })
            ->orderBy('match_date', 'asc')
            ->get();

        $suspensionsTriggered = 0;
        $suspensionsServed = 0;
        $yellowCount = 0;
        $yellowLimit = 2; // 2 yellows = 1 match suspension

        foreach ($matches as $match) {
            // Check if player served a suspension in this match
            $wasInLineup = $match->lineups()->where('players.id', $player->id)->exists();
            
            if (!$wasInLineup && $suspensionsTriggered > $suspensionsServed) {
                $suspensionsServed++;
            }

            // Check if player triggered a new suspension in this match
            $events = $match->matchEvents()->where('player_id', $player->id)->get();
            
            foreach ($events as $event) {
                if ($event->event_type === 'red_card') {
                    $suspensionsTriggered++;
                } elseif ($event->event_type === 'yellow_card') {
                    $yellowCount++;
                    if ($yellowCount % $yellowLimit === 0) {
                        $suspensionsTriggered++;
                    }
                }
            }
        }

        $isSuspended = $suspensionsTriggered > $suspensionsServed;
        
        $reason = null;
        if ($isSuspended) {
            $reason = "Suspension pending (" . ($suspensionsTriggered - $suspensionsServed) . " matches)";
        }

        return [
            'is_suspended' => $isSuspended,
            'reason' => $reason,
            'yellow_count' => $yellowCount,
            'remaining_yellows' => $yellowLimit - ($yellowCount % $yellowLimit),
            'suspensions_pending' => $suspensionsTriggered - $suspensionsServed
        ];
    }
}
