<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use Cloudinary\Cloudinary;

class ImageService
{
    protected $cloudinary;

    public function __construct()
    {
        // Initialize Cloudinary if credentials are configured
        if ($this->useCloudinary()) {
            $settings = Setting::whereIn('key', ['cloudinary_cloud_name', 'cloudinary_api_key', 'cloudinary_api_secret'])
                ->pluck('value', 'key');
            
            $this->cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => $settings['cloudinary_cloud_name'] ?? '',
                    'api_key' => $settings['cloudinary_api_key'] ?? '',
                    'api_secret' => $settings['cloudinary_api_secret'] ?? '',
                ],
            ]);
        }
    }

    /**
     * Check if Cloudinary is configured and enabled.
     */
    protected function useCloudinary()
    {
        $settings = Setting::whereIn('key', ['cloudinary_cloud_name', 'cloudinary_api_key', 'cloudinary_api_secret'])
            ->pluck('value', 'key');
        
        return !empty($settings['cloudinary_cloud_name']) && 
               !empty($settings['cloudinary_api_key']) && 
               !empty($settings['cloudinary_api_secret']);
    }
    /**
     * Upload an image or video to Cloudinary (if configured) or local storage.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string
     */
    public function upload($file, $folder = 'tournament')
    {
        if (!$file) return null;
        
        // Use Cloudinary if configured
        if ($this->useCloudinary()) {
            return $this->uploadToCloudinary($file, $folder);
        }
        
        // Fall back to local storage
        $path = $file->store($folder, 'public');
        $this->syncToPublic($path);
        return asset('storage/' . $path);
    }

    /**
     * Upload file to Cloudinary with automatic resource type detection.
     */
    protected function uploadToCloudinary($file, $folder)
    {
        try {
            $mimeType = $file->getMimeType();
            $resourceType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';
            
            $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => $resourceType,
                'use_filename' => true,
                'unique_filename' => true,
            ]);

            return $result['secure_url'];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Cloudinary Upload Failed: " . $e->getMessage());
            // Fall back to local storage on error
            $path = $file->store($folder, 'public');
            $this->syncToPublic($path);
            return asset('storage/' . $path);
        }
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
     * Delete an image/video from Cloudinary or local storage.
     *
     * @param string $url
     * @return void
     */
    public function delete($url)
    {
        if (!$url) return;

        // Check if it's a Cloudinary URL
        if (str_contains($url, 'res.cloudinary.com')) {
            $this->deleteFromCloudinary($url);
            return;
        }

        // Extract the path after 'storage/' for local files
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

    /**
     * Delete a file from Cloudinary.
     */
    protected function deleteFromCloudinary($url)
    {
        try {
            if (!$this->useCloudinary()) return;

            // Extract public_id from Cloudinary URL
            // URL format: https://res.cloudinary.com/{cloud_name}/{resource_type}/upload/v{version}/{public_id}.{format}
            $pattern = '/\/upload\/(?:v\d+\/)?(.+)\.\w+$/';
            if (preg_match($pattern, $url, $matches)) {
                $publicId = $matches[1];
                
                // Determine resource type from URL
                $resourceType = str_contains($url, '/video/') ? 'video' : 'image';
                
                $this->cloudinary->uploadApi()->destroy($publicId, [
                    'resource_type' => $resourceType,
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Cloudinary Delete Failed: " . $e->getMessage());
        }
    }
}
