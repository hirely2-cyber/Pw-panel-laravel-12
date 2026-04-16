<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizer
{
    /**
     * Store an uploaded image as WebP for optimal performance.
     * Falls back to original format if conversion fails.
     *
     * @param  UploadedFile  $file
     * @param  string  $directory  Storage directory (e.g. 'news', 'shop')
     * @param  string  $disk  Storage disk name
     * @param  int  $quality  WebP quality (1-100)
     * @return string  Stored file path
     */
    public static function storeAsWebp(UploadedFile $file, string $directory, string $disk = 'public', int $quality = 85): string
    {
        // Skip conversion for non-image types (ico, svg)
        $mime = $file->getMimeType();
        if (! str_starts_with($mime, 'image/') || in_array($mime, ['image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'])) {
            return $file->store($directory, $disk);
        }

        // Already WebP — store as-is
        if ($mime === 'image/webp') {
            return $file->store($directory, $disk);
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getPathname());
            $encoded = $image->toWebp($quality);

            $filename = uniqid() . '_' . time() . '.webp';
            $path = rtrim($directory, '/') . '/' . $filename;

            Storage::disk($disk)->put($path, (string) $encoded);

            return $path;
        } catch (\Throwable $e) {
            // Fallback: store original file if conversion fails
            return $file->store($directory, $disk);
        }
    }
}
