<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referee;
use App\Services\ImageService;
use Illuminate\Http\Request;

class AdminRefereeController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $referees = Referee::orderBy('name', 'asc')->get();
        return view('admin.referees.index', compact('referees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'has_fifa_badge' => 'nullable|boolean'
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $this->imageService->upload($request->file('image'), 'referees');
        }

        Referee::create([
            'name' => $request->name,
            'nationality' => $request->nationality,
            'image_url' => $imageUrl,
            'has_fifa_badge' => $request->has('has_fifa_badge')
        ]);

        return back()->with('success', 'Referee added successfully.');
    }

    public function update(Request $request, $id)
    {
        $referee = Referee::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'has_fifa_badge' => 'nullable|boolean'
        ]);

        if ($request->hasFile('image')) {
            $referee->image_url = $this->imageService->upload($request->file('image'), 'referees');
        }

        $referee->update([
            'name' => $request->name,
            'nationality' => $request->nationality,
            'has_fifa_badge' => $request->has('has_fifa_badge')
        ]);

        return back()->with('success', 'Referee updated.');
    }

    public function destroy($id)
    {
        $referee = Referee::findOrFail($id);
        $referee->delete();
        return back()->with('success', 'Referee deleted.');
    }
}
