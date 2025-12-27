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
            'competition_id' => 'required',
        ]);

        $compId = (int) $request->competition_id;
        
        // Diagnostic: Deeper inspection
        $rawExists = \Illuminate\Support\Facades\DB::table('competitions')->where('id', $compId)->exists();
        
        $dbName = \Illuminate\Support\Facades\DB::getDatabaseName();
        $prefix = \Illuminate\Support\Facades\DB::getTablePrefix();
        
        $groupsCreate = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE groups")[0]->{'Create Table'} ?? 'N/A';
        $compsCreate = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE competitions")[0]->{'Create Table'} ?? 'N/A';

        if (!$rawExists) {
            $allIds = \Illuminate\Support\Facades\DB::table('competitions')->pluck('id')->implode(', ');
            return back()->with('error', "Diagnostic Failed: ID $compId NOT found. Database: $dbName, Prefix: '$prefix'. Comps Available: [$allIds]");
        }

        try {
            \Illuminate\Support\Facades\DB::table('groups')->insert([
                'name' => $request->name,
                'competition_id' => $compId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            $msg = "DB Integrity Error: " . $e->getMessage() . " | DB: $dbName | Prefix: '$prefix' | GROUPS SQL: $groupsCreate | COMPS SQL: $compsCreate";
            return back()->with('error', $msg);
        }

        return back()->with('success', 'Group created successfully.');
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'competition_id' => 'required',
        ]);

        $compId = (int) $request->competition_id;

        // Diagnostic: Check record existence
        $rawExists = \Illuminate\Support\Facades\DB::table('competitions')->where('id', $compId)->exists();

        if (!$rawExists) {
            $allIds = \Illuminate\Support\Facades\DB::table('competitions')->pluck('id')->implode(', ');
            return back()->with('error', "Update Diagnostic Failed: Competition $compId NOT found in DB. Existing IDs are: [$allIds].");
        }

        try {
            \Illuminate\Support\Facades\DB::table('groups')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'competition_id' => $compId,
                    'updated_at' => now(),
                ]);
        } catch (\Exception $e) {
            return back()->with('error', "Update Integrity Error: " . $e->getMessage());
        }

        return back()->with('success', 'Group updated successfully.');
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return back()->with('success', 'Group deleted successfully.');
    }
}
