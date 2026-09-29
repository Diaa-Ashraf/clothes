<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Image;
use Spatie\Image\Enums\Format;

class ImageService
{
    /**
     * Store and convert an uploaded image to optimized WebP.
     *
     * @param UploadedFile $file
     * @param string $directory Directory inside public disk (e.g., 'products')
     * @param int $quality WebP quality (default: 82)
     * @param int $maxWidth Max width to resize if needed (default: 1200)
     * @return string Relative path stored in public disk
     */
    public function uploadAsWebp(UploadedFile $file, string $directory = 'products', int $quality = 82, int $maxWidth = 1200): string
    {
        $filename = Str::random(40) . '.webp';
        $relativeDir = trim($directory, '/');
        $relativePath = $relativeDir . '/' . $filename;
        
        $targetDisk = Storage::disk('public');
        $targetFullDir = $targetDisk->path($relativeDir);

        if (!file_exists($targetFullDir)) {
            @mkdir($targetFullDir, 0755, true);
        }

        $targetFullPath = $targetDisk->path($relativePath);

        try {
            // Attempt Spatie Image v3 webp conversion
            Image::load($file->getRealPath())
                ->quality($quality)
                ->optimize()
                ->save($targetFullPath);
        } catch (\Throwable $e) {
            // Fallback to GD native or direct store if Spatie driver fails
            try {
                if (function_exists('imagewebp') && function_exists('imagecreatefromstring')) {
                    $imgData = file_get_contents($file->getRealPath());
                    $source = imagecreatefromstring($imgData);
                    if ($source !== false) {
                        // Preserve transparency
                        imagepalettetotruecolor($source);
                        imagealphablending($source, true);
                        imagesavealpha($source, true);
                        imagewebp($source, $targetFullPath, $quality);
                        imagedestroy($source);
                        return $relativePath;
                    }
                }
            } catch (\Throwable $fallbackEx) {
                // Ignore fallback error and use standard store
            }

            // Ultimate fallback to normal store
            return $file->store($relativeDir, 'public');
        }

        return $relativePath;
    }

    /**
     * Delete an existing image from public disk safely.
     */
    public function deleteImage(?string $path): void
    {
        if ($path && $path !== 'default.jpg' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
