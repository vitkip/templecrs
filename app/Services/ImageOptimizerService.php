<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageOptimizerService
{
    /**
     * Resize, compress, and convert an uploaded image to WebP format.
     * Fully self-contained: works with native PHP GD or Intervention Image without crashing if vendor is missing.
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
            // Method 1: If Intervention Image v4 is installed and available
            if (class_exists(\Intervention\Image\ImageManager::class) && class_exists(\Intervention\Image\Drivers\Gd\Driver::class)) {
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $image = $manager->read($file->getRealPath());
                $image->scaleDown(width: $maxWidth, height: $maxHeight);
                $encoded = $image->toWebp($quality);

                $filename = rtrim($directory, '/') . '/' . uniqid('img_', true) . '.webp';
                Storage::disk('public')->put($filename, (string) $encoded);

                return $filename;
            }

            // Method 2: Native PHP GD (Zero composer dependency, works on all cPanel/PHP servers)
            if (function_exists('imagecreatefromstring') && function_exists('imagewebp') && function_exists('imagecreatetruecolor')) {
                return $this->optimizeWithNativeGd($file, $directory, $maxWidth, $maxHeight, $quality);
            }
        } catch (\Throwable $e) {
            Log::warning('Image optimization notice, storing original file: ' . $e->getMessage());
        }

        // Method 3: Safe fallback if image conversion cannot be performed
        return $file->store($directory, 'public');
    }

    /**
     * Native GD resize and WebP conversion.
     */
    protected function optimizeWithNativeGd(
        UploadedFile $file,
        string $directory,
        int $maxWidth,
        ?int $maxHeight,
        int $quality
    ): string {
        $realPath = $file->getRealPath();
        if (!$realPath || !file_exists($realPath)) {
            return $file->store($directory, 'public');
        }

        $sourceData = file_get_contents($realPath);
        if (!$sourceData) {
            return $file->store($directory, 'public');
        }

        $srcImg = @imagecreatefromstring($sourceData);
        if (!$srcImg) {
            return $file->store($directory, 'public');
        }

        $origW = imagesx($srcImg);
        $origH = imagesy($srcImg);

        if ($origW <= 0 || $origH <= 0) {
            imagedestroy($srcImg);
            return $file->store($directory, 'public');
        }

        // Calculate target dimensions proportionally
        $targetW = $origW;
        $targetH = $origH;

        if ($targetW > $maxWidth) {
            $ratio = $maxWidth / $targetW;
            $targetW = (int) round($targetW * $ratio);
            $targetH = (int) round($targetH * $ratio);
        }

        if ($maxHeight && $targetH > $maxHeight) {
            $ratio = $maxHeight / $targetH;
            $targetW = (int) round($targetW * $ratio);
            $targetH = (int) round($targetH * $ratio);
        }

        $targetW = max(1, $targetW);
        $targetH = max(1, $targetH);

        // Create target canvas
        $dstImg = imagecreatetruecolor($targetW, $targetH);

        // Preserve alpha channel transparency for PNG / WebP
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
        $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
        imagefilledrectangle($dstImg, 0, 0, $targetW, $targetH, $transparent);
        imagealphablending($dstImg, true);

        // Resample image
        imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);

        // Output to WebP buffer
        ob_start();
        imagewebp($dstImg, null, $quality);
        $webpContent = ob_get_clean();

        imagedestroy($srcImg);
        imagedestroy($dstImg);

        if (!$webpContent) {
            return $file->store($directory, 'public');
        }

        $filename = rtrim($directory, '/') . '/' . uniqid('img_', true) . '.webp';
        Storage::disk('public')->put($filename, $webpContent);

        return $filename;
    }
}
