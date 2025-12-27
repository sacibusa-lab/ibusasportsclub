<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Group;
use App\Models\MatchModel;
use App\Models\MatchEvent;
use App\Services\TournamentService;
use App\Services\ImageService;
use App\Models\MatchCommentary;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $tournamentService;
    protected $imageService;

    public function __construct(TournamentService $tournamentService, ImageService $imageService)
    {
        $this->tournamentService = $tournamentService;
        $this->imageService = $imageService;
    }

    public function index()
    {
        $pendingMatches = MatchModel::where('status', 'upcoming')->orderBy('match_date', 'asc')->get();
        return view('admin.dashboard', compact('pendingMatches'));
    }

    public function teams()
    {
        $groups = Group::with('competition')->get();
        $competitions = \App\Models\Competition::all();
        $teams = Team::with('players')->get();
        return view('admin.teams', compact('groups', 'teams', 'competitions'));
    }

    public function storeTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'manager' => 'nullable|string|max:255',
            'stadium_name' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:7',
            'group_id' => 'required|exists:groups,id',
            'logo' => 'nullable|image|max:2048'
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $logoUrl = $this->imageService->upload($request->file('logo'), 'teams');
        }

        Team::create([
            'name' => $request->name,
            'manager' => $request->manager,
            'stadium_name' => $request->stadium_name,
            'primary_color' => $request->primary_color,
            'group_id' => $request->group_id,
            'logo_url' => $logoUrl
        ]);

        return back()->with('success', 'Team added successfully.');
    }

    public function updateTeam(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'manager' => 'nullable|string|max:255',
            'stadium_name' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:7',
            'group_id' => 'required|exists:groups,id',
            'logo' => 'nullable|image|max:2048'
        ]);

        $updateData = [
            'name' => $request->name,
            'manager' => $request->manager,
            'stadium_name' => $request->stadium_name,
            'primary_color' => $request->primary_color,
            'group_id' => $request->group_id,
        ];

        if ($request->hasFile('logo')) {
            $updateData['logo_url'] = $this->imageService->upload($request->file('logo'), 'teams');
        }

        $team->update($updateData);

        return back()->with('success', 'Team updated successfully.');
    }

    public function fixtures()
    {
        $teams = Team::with('players')->get();
        $groups = Group::all();
        $competitions = \App\Models\Competition::all();
        $referees = \App\Models\Referee::all();
        $allMatches = MatchModel::orderBy('match_date', 'asc')->get();
        return view('admin.fixtures', compact('teams', 'groups', 'allMatches', 'referees', 'competitions'));
    }

    public function storeFixture(Request $request)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id',
            'competition_id' => 'required|exists:competitions,id',
            'match_date' => 'required|date',
            'venue' => 'nullable|string',
            'referee_id' => 'nullable|exists:referees,id',
            'referee_ar1_id' => 'nullable|exists:referees,id',
            'referee_ar2_id' => 'nullable|exists:referees,id',
            'attendance' => 'nullable|integer|min:0',
            'stage' => 'required|in:group,semifinal,final,novelty',
            'lineups_json' => 'nullable|json',
        ]);

    $homeTeam = Team::find($request->home_team_id);

    // Auto-calculate matchday based on date proximity or sequential increment
    $inputDate = \Carbon\Carbon::parse($request->match_date);
    $existingMatchInWindow = MatchModel::where('stage', $request->stage)
        ->whereBetween('match_date', [$inputDate->copy()->subDays(2), $inputDate->copy()->addDays(2)])
        ->whereNotNull('matchday')
        ->first();

    if ($existingMatchInWindow) {
        $matchday = $existingMatchInWindow->matchday;
    } else {
        $maxMatchday = MatchModel::where('stage', $request->stage)->max('matchday');
        $matchday = $maxMatchday ? $maxMatchday + 1 : 1;
    }

    $match = MatchModel::create([
        'home_team_id' => $request->home_team_id,
        'away_team_id' => $request->away_team_id,
        'match_date' => $request->match_date,
        'venue' => $request->venue,
        'referee_id' => $request->referee_id,
        'referee' => null, // Legacy column null
        'referee_ar1_id' => $request->referee_ar1_id,
        'referee_ar2_id' => $request->referee_ar2_id,
        'attendance' => $request->attendance,
        'stage' => $request->stage,
        'competition_id' => $request->competition_id,
        'group_id' => $request->stage === 'group' ? $homeTeam->group_id : null,
        'status' => 'upcoming',
        'matchday' => $matchday
    ]);

        // Process Lineups if provided
        $syncData = [];
        if ($request->has('home_lineup')) {
            foreach ($request->home_lineup as $playerId) {
                $syncData[$playerId] = ['team_id' => $request->home_team_id];
            }
        }
        if ($request->has('away_lineup')) {
            foreach ($request->away_lineup as $playerId) {
                $syncData[$playerId] = ['team_id' => $request->away_team_id];
            }
        }
        
        if (!empty($syncData)) {
            $match->lineups()->sync($syncData);
        } elseif ($request->lineups_json) {
            $lineups = json_decode($request->lineups_json, true);
            $syncData = [];
            foreach ($lineups as $lineup) {
                if (!isset($lineup['player_id'])) continue;
                $syncData[$lineup['player_id']] = [
                    'team_id' => $lineup['team_id'],
                    'position_key' => $lineup['position_key'] ?? null,
                    'position_x' => $lineup['position_x'] ?? null,
                    'position_y' => $lineup['position_y'] ?? null,
                    'shirt_number' => $lineup['shirt_number'] ?? null,
                    'is_captain' => $lineup['is_captain'] ?? false,
                    'is_substitute' => $lineup['is_substitute'] ?? false,
                ];
            }
            $match->lineups()->sync($syncData);
        }

        return redirect()->route('admin.fixtures.edit', $match->id)->with('success', 'Fixture created! Setup lineups and stats below.');
    }

    public function updateResult(Request $request, $id)
    {
        $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
        ]);

        $match = MatchModel::findOrFail($id);
        $match->update([
            'home_score' => $request->home_score,
            'away_score' => $request->away_score,
            'status' => 'finished'
        ]);

        if ($match->stage === 'group') {
            $this->tournamentService->updateStandings($match->group);
        }

        // Trigger Predictor League points calculation
        \App\Models\Prediction::calculatePointsForMatch($match->id);

        return back()->with('success', 'Result updated successfully.');
    }

    public function destroyTeam($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return back()->with('success', 'Team removed successfully.');
    }

    private static $pitchPositions = [
        'GK'   => ['x' => 6,  'y' => 50],
        'LB'   => ['x' => 25, 'y' => 15],
        'CB1'  => ['x' => 25, 'y' => 38],
        'CB2'  => ['x' => 25, 'y' => 62],
        'RB'   => ['x' => 25, 'y' => 85],
        'DM'   => ['x' => 40, 'y' => 50],
        'LM'   => ['x' => 65, 'y' => 15],
        'CM1'  => ['x' => 50, 'y' => 35],
        'CM2'  => ['x' => 50, 'y' => 65],
        'RM'   => ['x' => 65, 'y' => 85],
        'AM'   => ['x' => 65, 'y' => 50],
        'LW'   => ['x' => 85, 'y' => 20],
        'RW'   => ['x' => 85, 'y' => 80],
        'ST1'  => ['x' => 85, 'y' => 38],
        'ST2'  => ['x' => 85, 'y' => 62],
        'CF'   => ['x' => 92, 'y' => 50],
    ];

    public function editFixture($id)
    {
        $match = MatchModel::with([
            'homeTeam.players' => fn($q) => $q->withCount(['yellowCards', 'redCards']), 
            'awayTeam.players' => fn($q) => $q->withCount(['yellowCards', 'redCards']), 
            'lineups', 'matchEvents.team', 'assignedReferee', 'assignedAssistant1', 'assignedAssistant2', 'images'
        ])->findOrFail($id);
        $teams = Team::with('players')->get();
        $referees = \App\Models\Referee::all();
        $groups = Group::all();
        $positions = self::$pitchPositions;
        $competitions = \App\Models\Competition::all();
        return view('admin.edit-fixture', compact('match', 'teams', 'groups', 'positions', 'referees', 'competitions'));
    }

    public function updateFixture(Request $request, $id)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id',
            'competition_id' => 'required|exists:competitions,id',
            'match_date' => 'required|date',
            'venue' => 'nullable|string',
            'matchday' => 'nullable|integer|min:0',
            'broadcaster_logo' => 'nullable|string',
            'stage' => 'required|in:group,semifinal,final,novelty',
            'status' => 'required|in:upcoming,live,finished',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'home_possession' => 'nullable|integer|min:0|max:100',
            'away_possession' => 'nullable|integer|min:0|max:100',
            'home_shots' => 'nullable|integer|min:0',
            'away_shots' => 'nullable|integer|min:0',
            'home_corners' => 'nullable|integer|min:0',
            'away_corners' => 'nullable|integer|min:0',
            'home_offsides' => 'nullable|integer|min:0',
            'away_offsides' => 'nullable|integer|min:0',
            'home_fouls' => 'nullable|integer|min:0',
            'away_fouls' => 'nullable|integer|min:0',
            'home_free_kicks' => 'nullable|integer|min:0',
            'away_free_kicks' => 'nullable|integer|min:0',
            'home_throw_ins' => 'nullable|integer|min:0',
            'away_throw_ins' => 'nullable|integer|min:0',
            'home_saves' => 'nullable|integer|min:0',
            'away_saves' => 'nullable|integer|min:0',
            'home_goal_kicks' => 'nullable|integer|min:0',
            'away_goal_kicks' => 'nullable|integer|min:0',
            'home_missed_chances' => 'nullable|integer|min:0',
            'away_missed_chances' => 'nullable|integer|min:0',
            'home_scorers' => 'nullable|string',
            'away_scorers' => 'nullable|string',
            'report' => 'nullable|string',
            'motm_player_id' => 'nullable|exists:players,id',
            'referee_id' => 'nullable|exists:referees,id',
            'referee_ar1_id' => 'nullable|exists:referees,id',
            'referee_ar2_id' => 'nullable|exists:referees,id',
            'attendance' => 'nullable|integer|min:0',
            'lineups_json' => 'nullable|json',
            'highlights_video' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime|max:51200', // 50MB max
            'highlights_thumbnail' => 'nullable|image|max:5120', // 5MB max
        ]);

        $match = MatchModel::findOrFail($id);
        $homeTeam = Team::find($request->home_team_id);

        $updateData = [
            'home_team_id' => $request->home_team_id,
            'away_team_id' => $request->away_team_id,
            'competition_id' => $request->competition_id,
            'match_date'   => $request->match_date,
            'venue'        => $request->venue,
            'matchday'     => $request->matchday,
            'broadcaster_logo' => $request->broadcaster_logo,
            'stage'        => $request->stage,
            'group_id'     => $request->stage === 'group' ? $homeTeam->group_id : null,
            'status'       => $request->status,
            'home_score'   => $request->home_score,
            'away_score'   => $request->away_score,
            'home_possession' => $request->home_possession ?? 50,
            'away_possession' => $request->away_possession ?? 50,
            'home_shots'   => $request->home_shots ?? 0,
            'away_shots'   => $request->away_shots ?? 0,
            'home_corners' => $request->home_corners ?? 0,
            'away_corners' => $request->away_corners ?? 0,
            'home_offsides' => $request->home_offsides ?? 0,
            'away_offsides' => $request->away_offsides ?? 0,
            'home_fouls'   => $request->home_fouls ?? 0,
            'away_fouls'   => $request->away_fouls ?? 0,
            'home_free_kicks' => $request->home_free_kicks ?? 0,
            'away_free_kicks' => $request->away_free_kicks ?? 0,
            'home_throw_ins' => $request->home_throw_ins ?? 0,
            'away_throw_ins' => $request->away_throw_ins ?? 0,
            'home_saves'   => $request->home_saves ?? 0,
            'away_saves'   => $request->away_saves ?? 0,
            'home_goal_kicks' => $request->home_goal_kicks ?? 0,
            'away_goal_kicks' => $request->away_goal_kicks ?? 0,
            'home_missed_chances' => $request->home_missed_chances ?? 0,
            'away_missed_chances' => $request->away_missed_chances ?? 0,
            'home_scorers' => $request->home_scorers,
            'away_scorers' => $request->away_scorers,
            'report'       => $request->report,
            'motm_player_id' => $request->motm_player_id,
            'referee_id'   => $request->referee_id,
            'referee_ar1_id' => $request->referee_ar1_id,
            'referee_ar2_id' => $request->referee_ar2_id,
            'attendance'   => $request->attendance,
        ];

        // Handle Highlights URL or Upload
        if ($request->hasFile('highlights_video')) {
            // Delete old video if it exists on Cloudinary/Local
            if ($match->highlights_url) {
                $this->imageService->delete($match->highlights_url);
            }
            $updateData['highlights_url'] = $this->imageService->upload($request->file('highlights_video'), 'matches/highlights');
        } else {
            $updateData['highlights_url'] = $request->highlights_url;
        }

        if ($request->hasFile('highlights_thumbnail')) {
            if ($match->highlights_thumbnail) {
                $this->imageService->delete($match->highlights_thumbnail);
            }
            $updateData['highlights_thumbnail'] = $this->imageService->upload($request->file('highlights_thumbnail'), 'matches/thumbnails');
        }

        $match->update($updateData);

        // Process Lineups
        if ($request->has('lineup')) {
            $syncData = [];
            $positions = self::$pitchPositions;

            foreach ($request->lineup as $playerId => $data) {
                if ($data['status'] === 'start' || $data['status'] === 'sub') {
                    $isSub = $data['status'] === 'sub';
                    $posKey = $data['position'] ?? null;
                    $coords = ($posKey && isset($positions[$posKey])) ? $positions[$posKey] : ['x' => null, 'y' => null];
                    
                    $player = \App\Models\Player::find($playerId);
                    if ($coords['x']) {
                        if ($player && (string)$player->team_id === (string)$request->home_team_id) {
                            $coords['x'] = $coords['x'] * 0.45;
                        } else {
                            $coords['x'] = 100 - ($coords['x'] * 0.45);
                        }
                    }

                    $syncData[$playerId] = [
                        'team_id' => $player->team_id ?? ($playerId % 2 == 0 ? $request->home_team_id : $request->away_team_id),
                        'is_substitute' => $isSub,
                        'position_key' => $posKey,
                        'position_x' => $coords['x'],
                        'position_y' => $coords['y'],
                        'shirt_number' => $player->shirt_number ?? null
                    ];
                }
            }
            // Always sync even if empty, to allow clearing
            $match->lineups()->sync($syncData);

        } elseif ($request->lineups_json) {
            $lineups = json_decode($request->lineups_json, true);
            $syncData = [];
            foreach ($lineups as $lineup) {
                if (!isset($lineup['player_id'])) continue;
                $syncData[$lineup['player_id']] = [
                    'team_id' => $lineup['team_id'],
                    'position_key' => $lineup['position_key'] ?? null,
                    'position_x' => $lineup['position_x'] ?? null,
                    'position_y' => $lineup['position_y'] ?? null,
                    'shirt_number' => $lineup['shirt_number'] ?? null,
                    'is_captain' => $lineup['is_captain'] ?? false,
                    'is_substitute' => $lineup['is_substitute'] ?? false,
                ];
            }
            $match->lineups()->sync($syncData);
        }

        if ($match->stage === 'group' && $match->group) {
            $this->tournamentService->updateStandings($match->group);
        }

        if ($match->status === 'finished') {
            \App\Models\Prediction::calculatePointsForMatch($match->id);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Fixture updated successfully.']);
        }

        return back()->with('success', 'Fixture updated successfully.');
    }

    public function destroyFixture($id)
    {
        $match = MatchModel::findOrFail($id);
        $group = $match->group;
        $match->delete();

        if ($group) {
            $this->tournamentService->updateStandings($group);
        }

        return back()->with('success', 'Fixture removed successfully.');
    }

    public function storeEvent(Request $request, $matchId)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'nullable|exists:players,id',
            'assist_player_id' => 'nullable|exists:players,id',
            'related_player_id' => 'nullable|exists:players,id',
            'player_name' => 'nullable|string|max:255',
            'player_image' => 'nullable|image|max:2048',
            'event_type' => 'required|in:goal,yellow_card,red_card,penalty,sub_on,sub_off',
            'minute' => 'required|integer|min:0|max:120',
        ]);

        $imageUrl = null;
        if ($request->hasFile('player_image')) {
            $imageUrl = $this->imageService->upload($request->file('player_image'), 'players');
        }

        // If player_id is provided, get the name for legacy support
        $playerName = $request->player_name;
        if ($request->player_id) {
            $player = \App\Models\Player::find($request->player_id);
            $playerName = $player->name;
            $imageUrl = $imageUrl ?? $player->image_url;
        }

        $event = MatchEvent::create([
            'match_id' => $matchId,
            'team_id' => $request->team_id,
            'player_id' => $request->player_id,
            'assist_player_id' => $request->assist_player_id,
            'related_player_id' => $request->related_player_id,
            'player_name' => $playerName,
            'player_image_url' => $imageUrl,
            'event_type' => $request->event_type,
            'minute' => $request->minute,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Event added successfully.',
                'event' => [
                    'id' => $event->id,
                    'minute' => $event->minute,
                    'type' => str_replace('_', ' ', $event->event_type),
                    'team' => $event->team->name,
                    'player_name' => $event->player_name,
                    'player_image' => $event->player_image_url ?: null,
                    'initial' => substr($event->player_name, 0, 1),
                    'extra' => $event->event_type == 'goal' && $event->assistant 
                                ? 'assist: ' . $event->assistant->name 
                                : ($event->event_type == 'sub_on' && $event->relatedPlayer 
                                    ? 'replacing: ' . $event->relatedPlayer->name 
                                    : null),
                    'delete_url' => route('admin.matches.events.destroy', $event->id)
                ]
            ]);
        }

        return back()->with('success', 'Event added successfully.');
    }

    public function destroyEvent($id)
    {
        $event = MatchEvent::findOrFail($id);
        $event->delete();
        return back()->with('success', 'Event deleted.');
    }

    // Removed Live Reporting Methods

    public function storeMatchImage(Request $request, $matchId)
    {
        $request->validate([
            'image' => 'nullable|image|max:5120',
            'image_url' => 'nullable|url',
            'caption' => 'nullable|string|max:255'
        ]);

        $match = MatchModel::findOrFail($matchId);
        $finalUrl = null;

        if ($request->hasFile('image')) {
            $finalUrl = $this->imageService->upload($request->file('image'), 'matches/gallery');
        } elseif ($request->image_url) {
            $finalUrl = $request->image_url;
        }

        if (!$finalUrl) {
            return back()->with('error', 'Please provide an image file or a valid URL.');
        }

        \App\Models\MatchImage::create([
            'match_id' => $matchId,
            'image_url' => $finalUrl,
            'caption' => $request->caption,
            'order' => $match->images()->max('order') + 1
        ]);

        return back()->with('success', 'Image added to gallery.');
    }

    public function destroyMatchImage($id)
    {
        $image = \App\Models\MatchImage::findOrFail($id);
        
        // If it's a local storage image, delete the file
        if (str_contains($image->image_url, asset('storage/'))) {
            $this->imageService->delete($image->image_url);
        }

        $image->delete();
        return back()->with('success', 'Image removed from gallery.');
    }

    public function startMatch(Request $request, $id)
    {
        $match = MatchModel::findOrFail($id);
        
        $match->update([
            'prediction_closes_at' => now()->addMinutes(5)
        ]);

        return back()->with('success', 'Match started! Predictions will close in 5 minutes.');
    }

    /**
     * Upload multiple images to match gallery
     */
    public function uploadGalleryImages(Request $request, $id)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        $match = MatchModel::findOrFail($id);
        $uploadedCount = 0;

        if ($request->hasFile('images')) {
            // Get the current highest order number
            $maxOrder = \App\Models\MatchImage::where('match_id', $id)->max('order') ?? 0;

            foreach ($request->file('images') as $index => $image) {
                try {
                    $imageUrl = $this->imageService->upload($image, 'match-gallery');
                    $caption = $request->input("captions.{$index}");

                    \App\Models\MatchImage::create([
                        'match_id' => $id,
                        'image_url' => $imageUrl,
                        'caption' => $caption,
                        'order' => ++$maxOrder,
                    ]);

                    $uploadedCount++;
                } catch (\Exception $e) {
                    \Log::error("Failed to upload gallery image: " . $e->getMessage());
                }
            }
        }

        return back()->with('success', "Successfully uploaded {$uploadedCount} image(s) to gallery.");
    }

    /**
     * Delete a single image from match gallery
     */
    public function deleteGalleryImage($matchId, $imageId)
    {
        $image = \App\Models\MatchImage::where('match_id', $matchId)
            ->where('id', $imageId)
            ->firstOrFail();

        // Delete the image from storage (Cloudinary or local)
        $this->imageService->delete($image->image_url);

        // Delete database record
        $image->delete();

        return back()->with('success', 'Image deleted from gallery.');
    }

    public function liveConsole()
    {
        $matches = \App\Models\MatchModel::whereIn('status', ['upcoming', 'live'])
            ->orderByRaw("CASE WHEN status = 'live' THEN 1 ELSE 2 END")
            ->orderBy('match_date', 'asc')
            ->get();
        return view('admin.live-console.index', compact('matches'));
    }

    public function liveConsoleControl($id)
    {
        $match = \App\Models\MatchModel::with(['homeTeam.players', 'awayTeam.players', 'lineups', 'matchEvents'])
            ->findOrFail($id);
        
        $homeXI = $match->lineups()
            ->where('match_lineups.team_id', $match->home_team_id)
            ->where('is_substitute', false)
            ->get();
            
        $homeSubs = $match->lineups()
            ->where('match_lineups.team_id', $match->home_team_id)
            ->where('is_substitute', true)
            ->get();
            
        $awayXI = $match->lineups()
            ->where('match_lineups.team_id', $match->away_team_id)
            ->where('is_substitute', false)
            ->get();
            
        $awaySubs = $match->lineups()
            ->where('match_lineups.team_id', $match->away_team_id)
            ->where('is_substitute', true)
            ->get();

        return view('admin.live-console.control', compact('match', 'homeXI', 'homeSubs', 'awayXI', 'awaySubs'));
    }

    public function startMatchTimer(Request $request, $id)
    {
        $match = MatchModel::findOrFail($id);
        $match->update([
            'status' => 'live',
            'started_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'started_at' => $match->started_at->toIso8601String()
        ]);
    }

    public function endMatch(Request $request, $id)
    {
        $match = MatchModel::findOrFail($id);
        $match->update(['status' => 'finished']);

        if ($match->stage === 'group' && $match->group) {
            $this->tournamentService->updateStandings($match->group);
        }

        // Calculate prediction points
        if (class_exists('\App\Models\Prediction')) {
            \App\Models\Prediction::calculatePointsForMatch($match->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Match ended and points calculated.'
        ]);
    }

    public function updateQuickStat(\Illuminate\Http\Request $request, $id)
    {
        $match = \App\Models\MatchModel::findOrFail($id);
        $side = $request->side; // 'home' or 'away'
        $stat = $request->stat; // e.g. 'corners', 'shots'
        
        $column = "{$side}_{$stat}";
        
        // Allowed stats to prevent arbitrary column updates
        $allowedStats = [
            'shots', 'corners', 'offsides', 'fouls', 'free_kicks', 
            'throw_ins', 'saves', 'goal_kicks', 'missed_chances'
        ];

        if (!in_array($stat, $allowedStats)) {
            return response()->json(['error' => 'Invalid stat'], 400);
        }

        $match->increment($column);
        
        return response()->json([
            'success' => true,
            'new_value' => $match->$column,
            'stat' => $stat,
            'side' => $side
        ]);
    }

    public function storeQuickEvent(\Illuminate\Http\Request $request, $id)
    {
        $match = \App\Models\MatchModel::findOrFail($id);
        
        $event = \App\Models\MatchEvent::create([
            'match_id' => $id,
            'team_id' => $request->team_id,
            'player_id' => $request->player_id,
            'player_name' => \App\Models\Player::find($request->player_id)->name ?? 'Unknown',
            'event_type' => $request->event_type,
            'minute' => $request->minute ?? 0,
        ]);

        if ($request->event_type === 'goal' || $request->event_type === 'penalty_goal') {
            if ($request->team_id == $match->home_team_id) {
                $match->increment('home_score');
            } else {
                $match->increment('away_score');
            }
            
            if ($match->stage === 'group' && $match->group) {
                $this->tournamentService->updateStandings($match->group);
            }
        }

        return response()->json([
            'success' => true,
            'home_score' => $match->home_score,
            'away_score' => $match->away_score,
            'event' => $event
        ]);
    }
}
