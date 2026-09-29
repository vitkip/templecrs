<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageOptimizerService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Resize, compress, and convert an uploaded image to WebP format.
     *
     * @param UploadedFile $file The uploaded image file
     * @param string $directory Subdirectory on the 'public' disk (e.g. 'news/covers')
     * @param int $maxWidth Maximum width in pixels (proportional scaling)
     * @param int|null $maxHeight Maximum height in pixels (optional)
     * @param int $quality WebP quality (1-100, default 82)
     * @return string Stored file path relative to the public disk
     */
    public function optimizeAndStore(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1400,
        ?int $maxHeight = null,
        int $quality = 82
    ): string {
        try {
            // Read image from temporary upload path
            $image = $this->manager->read($file->getRealPath());

            // Proportional downscale only if dimensions exceed maximums
            $image->scaleDown(width: $maxWidth, height: $maxHeight);

            // Encode to modern WebP format
            $encoded = $image->toWebp($quality);

            // Generate clean unique filename
            $filename = rtrim($directory, '/') . '/' . uniqid('img_', true) . '.webp';

            // Store on public disk
            Storage::disk('public')->put($filename, (string) $encoded);

            return $filename;
        } catch (\Throwable $e) {
            Log::warning('Image optimization failed, falling back to original upload: ' . $e->getMessage());

            // Graceful fallback: store original file untouched
            return $file->store($directory, 'public');
        }
    }
}
