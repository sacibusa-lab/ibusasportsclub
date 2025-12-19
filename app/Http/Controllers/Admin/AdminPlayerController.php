<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use App\Services\ImageService;
use Illuminate\Http\Request;

class AdminPlayerController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $teams = Team::all();
        $players = Player::with('team')->get();
        return view('admin.players.index', compact('teams', 'players'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|exists:teams,id',
            'position' => 'required|in:GK,DEF,MID,FWD',
            'shirt_number' => 'nullable|integer|min:1|max:99',
            'image' => 'nullable|image|max:2048',
            'full_image' => 'nullable|image|max:3072',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $this->imageService->upload($request->file('image'), 'players');
        }

        $fullImageUrl = null;
        if ($request->hasFile('full_image')) {
            $fullImageUrl = $this->imageService->upload($request->file('full_image'), 'players/full');
        }

        Player::create([
            'name' => $request->name,
            'team_id' => $request->team_id,
            'position' => $request->position,
            'shirt_number' => $request->shirt_number,
            'image_url' => $imageUrl,
            'full_image_url' => $fullImageUrl,
        ]);

        return back()->with('success', 'Player added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|exists:teams,id',
            'position' => 'required|in:GK,DEF,MID,FWD',
            'shirt_number' => 'nullable|integer|min:1|max:99',
            'image' => 'nullable|image|max:2048',
            'full_image' => 'nullable|image|max:3072',
        ]);

        $player = Player::findOrFail($id);
        $imageUrl = $player->image_url;
        $fullImageUrl = $player->full_image_url;

        if ($request->hasFile('image')) {
            $imageUrl = $this->imageService->upload($request->file('image'), 'players');
        }

        if ($request->hasFile('full_image')) {
            $fullImageUrl = $this->imageService->upload($request->file('full_image'), 'players/full');
        }

        $player->update([
            'name' => $request->name,
            'team_id' => $request->team_id,
            'position' => $request->position,
            'shirt_number' => $request->shirt_number,
            'image_url' => $imageUrl,
            'full_image_url' => $fullImageUrl,
        ]);

        return back()->with('success', 'Player updated successfully.');
    }

    public function destroy($id)
    {
        Player::findOrFail($id)->delete();
        return back()->with('success', 'Player removed.');
    }
}
