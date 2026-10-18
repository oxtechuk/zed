<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    public const MAX_WIDTH = 1600;
    public const QUALITY = 80;

    /**
     * Store and optimize an uploaded image as WebP.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return string Path relative to disk
     */
    public function storeAsWebp(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): string
    {
        return $this->storeAndOptimize($file, $directory, [], $disk);
    }

    /**
     * Store and optimize an uploaded image with custom options.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array{maxWidth?: int, quality?: int} $options
     * @param string $disk
     * @return string
     */
    public function storeAndOptimize(UploadedFile $file, string $directory = 'uploads', array $options = [], string $disk = 'public'): string
    {
        $maxWidth = $options['maxWidth'] ?? self::MAX_WIDTH;
        $quality = $options['quality'] ?? self::QUALITY;

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(40) . '.webp';
        $targetPath = trim($directory, '/') . '/' . $filename;

        // If GD extension is not available or SVG, fallback to standard store
        if (! function_exists('imagecreatefromstring') || $extension === 'svg') {
            return $file->store($directory, $disk);
        }

        $imageContent = file_get_contents($file->getRealPath());
        if ($imageContent === false) {
            return $file->store($directory, $disk);
        }

        $optimizedWebp = $this->convertToWebp($imageContent, $maxWidth, $quality);

        if ($optimizedWebp === null) {
            return $file->store($directory, $disk);
        }

        Storage::disk($disk)->put($targetPath, $optimizedWebp);

        return $targetPath;
    }

    /**
     * Convert binary image data to WebP with max dimensions and quality.
     */
    public function convertToWebp(string $binaryData, int $maxWidth = self::MAX_WIDTH, int $quality = self::QUALITY): ?string
    {
        $image = @imagecreatefromstring($binaryData);
        if (! $image) {
            return null;
        }

        // Handle transparency
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round(($height / $width) * $newWidth);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        ob_start();
        $success = imagewebp($image, null, $quality);
        $result = ob_get_clean();
        imagedestroy($image);

        return ($success && $result) ? $result : null;
    }
}
