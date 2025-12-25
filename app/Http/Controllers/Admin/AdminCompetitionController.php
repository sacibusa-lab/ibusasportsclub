<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Competition;
use Illuminate\Support\Str;

class AdminCompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::orderBy('created_at', 'desc')->get();
        return view('admin.competitions.index', compact('competitions'));
    }

    public function create()
    {
        return view('admin.competitions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:league,knockout,novelty',
            'is_active' => 'nullable',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Competition::create($validated);

        return redirect()->route('admin.competitions.index')->with('success', 'Competition created successfully!');
    }

    public function edit(Competition $competition)
    {
        return view('admin.competitions.edit', compact('competition'));
    }

    public function update(Request $request, Competition $competition)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:league,knockout,novelty',
            'is_active' => 'nullable',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $competition->update($validated);

        return redirect()->route('admin.competitions.index')->with('success', 'Competition updated successfully!');
    }

    public function destroy(Competition $competition)
    {
        $competition->delete();
        return redirect()->route('admin.competitions.index')->with('success', 'Competition deleted successfully!');
    }
}
