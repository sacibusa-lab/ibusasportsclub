<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ImageService;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        // Define default values if not set
        $defaults = [
            'site_name' => 'LOCAL CHAMPIONSHIP',
            'site_short_name' => 'LC',
            'contact_email' => 'admin@tournament.com',
            'primary_color' => '#3d195b',
            'secondary_color' => '#00ff85',
            'accent_color' => '#ff005a',
            'current_season' => date('Y'),
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($settings[$key])) {
                $settings[$key] = $value;
            }
        }

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'site_logo']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->hasFile('site_logo')) {
            $logoUrl = $this->imageService->upload($request->file('site_logo'), 'branding');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $logoUrl]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
