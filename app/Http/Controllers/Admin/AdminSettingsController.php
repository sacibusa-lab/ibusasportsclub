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
            'footer_text' => 'Local Community Football Championship. Built with Laravel. Not affiliated with the Premier League.',
            'copyright_text' => '© ' . date('Y') . ' Local Community Football Championship.',
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
        $data = $request->except(['_token', '_method', 'site_logo', 'favicon', 'site_icon']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $imageFields = ['site_logo', 'favicon', 'site_icon'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $url = $this->imageService->upload($request->file($field), 'branding');
                Setting::updateOrCreate(['key' => $field], ['value' => $url]);
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function fixStorage()
    {
        try {
            // Attempt standard link
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            $message = \Illuminate\Support\Facades\Artisan::output();

            // Additional manual check for cPanel public_html
            $publicHtml = base_path('../public_html/storage');
            $storagePath = storage_path('app/public');

            if (is_dir(base_path('../public_html')) && !file_exists($publicHtml)) {
                if (symlink($storagePath, $publicHtml)) {
                    $message .= " | Created symlink for public_html/storage";
                }
            }

            return back()->with('success', 'Storage link attempt completed: ' . $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to link storage: ' . $e->getMessage());
        }
    }
}
