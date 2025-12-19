<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use App\Services\ImageService;
use Illuminate\Http\Request;

class AdminSponsorController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    public function index()
    {
        $sponsors = Sponsor::orderBy('level')->orderBy('order')->get();
        $levels = [
            'Lead Partner',
            'Official Creativity Partner',
            'Official Bank',
            'Official Soft Drink',
            'Official Beer',
            'Official Cloud & AI Partner',
            'Official Ball',
            'Official Licensee',
            'Official Broadcaster'
        ];
        return view('admin.sponsors.index', compact('sponsors', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'level' => 'required|string',
            'order' => 'integer'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_url'] = $this->imageService->upload($request->file('logo'), 'sponsors');
        }

        Sponsor::create($validated);

        return back()->with('success', 'Sponsor added successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'level' => 'required|string',
            'order' => 'integer'
        ]);

        $sponsor = Sponsor::findOrFail($id);

        if ($request->hasFile('logo')) {
            $validated['logo_url'] = $this->imageService->upload($request->file('logo'), 'sponsors');
        }

        $sponsor->update($validated);

        return back()->with('success', 'Sponsor updated successfully.');
    }

    public function destroy($id)
    {
        Sponsor::findOrFail($id)->delete();
        return back()->with('success', 'Sponsor removed.');
    }
}
