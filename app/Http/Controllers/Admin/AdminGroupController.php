<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Competition;
use Illuminate\Http\Request;

class AdminGroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('competition', 'teams')->get();
        $competitions = Competition::all();
        return view('admin.groups.index', compact('groups', 'competitions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'competition_id' => 'required|exists:competitions,id',
        ]);

        $competition = Competition::findOrFail($request->competition_id);
        
        $competition->groups()->create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Group created successfully.');
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'competition_id' => 'required|exists:competitions,id',
        ]);

        $competition = Competition::findOrFail($request->competition_id);

        $group->update([
            'name' => $request->name,
            'competition_id' => $competition->id,
        ]);

        return back()->with('success', 'Group updated successfully.');
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return back()->with('success', 'Group deleted successfully.');
    }
}
