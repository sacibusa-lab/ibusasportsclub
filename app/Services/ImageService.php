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

        // Return the public URL using asset() for better portability
        return asset('storage/' . $path);
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
    }
}
