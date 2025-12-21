<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Upload an image to local storage and return the public URL.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string
     */
    public function upload($file, $folder = 'tournament')
    {
        if (!$file) return null;
        
        // Store on the 'public' disk
        $path = $file->store($folder, 'public');

        // Automate the sync to bypass cPanel symlink issues
        $this->syncToPublic($path);

        // Return the public URL using asset() for better portability
        return asset('storage/' . $path);
    }

    /**
     * Sync a specific file to all potential public storage locations.
     * This bypasses symlink issues on restrictive hosts like cPanel.
     */
    protected function syncToPublic($relativePath)
    {
        try {
            $source = storage_path('app/public/' . $relativePath);
            
            $destinations = [
                public_path('storage/' . $relativePath),
                base_path('../public_html/storage/' . $relativePath),
            ];

            // Add DOCUMENT_ROOT if available
            if (!empty($_SERVER['DOCUMENT_ROOT'])) {
                $destinations[] = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $relativePath;
            }

            foreach ($destinations as $dest) {
                // Skip if destination is same as source
                if (realpath($dest) === realpath($source)) continue;

                $directory = dirname($dest);
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                if (file_exists($source)) {
                    copy($source, $dest);
                    chmod($dest, 0644);
                }
            }
        } catch (\Exception $e) {
            // Silently fail to not break the upload flow
            \Illuminate\Support\Facades\Log::error("Image Sync Failed: " . $e->getMessage());
        }
    }

    /**
     * Delete an image from local storage.
     *
     * @param string $url
     * @return void
     */
    public function delete($url)
    {
        if (!$url) return;

        // Extract the path after 'storage/' regardless of domain
        $parts = explode('/storage/', $url);
        $path = end($parts);
        $path = ltrim($path, '/');

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Also delete from sync locations
        $destinations = [
            public_path('storage/' . $path),
            base_path('../public_html/storage/' . $path),
        ];

        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            $destinations[] = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $path;
        }

        foreach ($destinations as $dest) {
            if (file_exists($dest)) {
                @unlink($dest);
            }
        }
    }
}
