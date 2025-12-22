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
            'analytics_whitelist_ips' => '127.0.0.1, ::1',
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
            $message = "";
            $realPublicPath = public_path();
            $realStoragePath = storage_path('app/public');
            $message .= "Active Public Path: $realPublicPath | ";

            $targets = [
                public_path('storage'),
                base_path('../public_html/storage')
            ];

            foreach ($targets as $target) {
                if (strpos($target, 'public_html') !== false && !is_dir(dirname($target))) continue;

                if (file_exists($target) || is_link($target)) {
                    if (is_link($target)) {
                        unlink($target);
                        $message .= "Deleted link at " . basename(dirname($target)) . ". ";
                    } else {
                        // If it's a directory, we keep it but ensure permissions
                        $message .= "Found physical directory at " . basename(dirname($target)) . ". ";
                    }
                }

                // If symlinks are failing, we can try to create a real folder and copy
                // For now, let's try the link again but with more robust check
                try {
                    if (!file_exists($target)) {
                        if (symlink($realStoragePath, $target)) {
                            $message .= "Linked " . basename(dirname($target)) . " successfully. ";
                        }
                    }
                } catch (\Exception $e) {
                    $message .= "Link failed for " . basename(dirname($target)) . ": " . $e->getMessage() . ". ";
                }
            }

            // 4. Update .htaccess to allow symlinks if missing (Check both public and public_html)
            $pathsToCheck = [public_path('.htaccess'), base_path('../public_html/.htaccess')];
            foreach ($pathsToCheck as $htaccessPath) {
                if (file_exists($htaccessPath)) {
                    $htaccess = file_get_contents($htaccessPath);
                    $directives = [
                        'Options +FollowSymLinks',
                        'Options +SymLinksIfOwnerMatch'
                    ];
                    
                    $updated = false;
                    if (strpos($htaccess, 'Options +FollowSymLinks') === false && strpos($htaccess, 'Options +SymLinksIfOwnerMatch') === false) {
                        $htaccess = "Options +FollowSymLinks\n" . $htaccess;
                        $updated = true;
                    }
                    
                    if ($updated) {
                        file_put_contents($htaccessPath, $htaccess);
                        $message .= " | Updated " . basename(dirname($htaccessPath)) . "/.htaccess";
                    }
                }
            }

            // 5. Attempt to set permissions
            if (is_dir($realStoragePath)) {
                $this->chmod_r($realStoragePath);
                $message .= " | Finalized permissions.";
            }

            return back()->with('success', 'Deep Storage Fix Completed: ' . $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reset storage link: ' . $e->getMessage());
        }
    }

    public function syncStorage()
    {
        try {
            $source = storage_path('app/public');
            $message = "";

            $destinations = [
                public_path('storage'),
                base_path('../public_html/storage'),
                ($_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'] . '/storage' : null)
            ];

            foreach ($destinations as $dest) {
                if (!$dest) continue;
                
                // If it's the SAME path as source, skip (safety)
                if (realpath($dest) === realpath($source)) continue;

                if (!is_dir($dest)) {
                    mkdir($dest, 0755, true);
                }

                \Illuminate\Support\Facades\File::copyDirectory($source, $dest);
                $this->chmod_r($dest);
                $message .= "Synced to " . basename(dirname($dest)) . ". ";
            }

            return back()->with('success', "Files synchronized successfully: $message");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync files: ' . $e->getMessage());
        }
    }

    private function chmod_r($path) {
        if (!file_exists($path)) return;
        
        $dir = new \DirectoryIterator($path);
        foreach ($dir as $item) {
            if ($item->isDot()) continue;
            
            if ($item->isDir()) {
                chmod($item->getPathname(), 0755);
                $this->chmod_r($item->getPathname());
            } else {
                chmod($item->getPathname(), 0644);
            }
        }
        chmod($path, 0755);
    }
}
