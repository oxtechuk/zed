<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    /**
     * Store and optimize an uploaded image file.
     *
     * @param  array{maxWidth?: int, maxHeight?: int, quality?: int, format?: string, disk?: string}  $options
     * @return string Relative path on the storage disk (e.g. "cars/thumbnails/xyz.webp")
     */
    public function storeAndOptimize(UploadedFile $file, string $directory, array $options = []): string
    {
        $disk = $options['disk'] ?? 'public';
        $maxWidth = $options['maxWidth'] ?? 1600;
        $maxHeight = $options['maxHeight'] ?? 1600;
        $quality = $options['quality'] ?? 82;
        $convertToWebp = ($options['format'] ?? 'webp') === 'webp';

        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType() ?: $file->getClientMimeType();

        // If not an image that GD can process (e.g. svg, pdf, video), store normally
        if (! $this->isProcessableImage($mimeType, $extension)) {
            return $file->store($directory, $disk);
        }

        $imageResource = $this->createImageResource($file->getRealPath(), $mimeType, $extension);
        if (! $imageResource) {
            return $file->store($directory, $disk);
        }

        // Correct EXIF orientation if applicable
        $imageResource = $this->correctOrientation($imageResource, $file->getRealPath());

        // Resize proportionally if dimensions exceed max
        $imageResource = $this->resizeIfNeeded($imageResource, $maxWidth, $maxHeight);

        $filename = Str::random(40).($convertToWebp ? '.webp' : '.'.$extension);
        $relativePath = trim($directory, '/').'/'.$filename;
        $storageDir = Storage::disk($disk)->path(trim($directory, '/'));

        if (! is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $targetFullPath = Storage::disk($disk)->path($relativePath);

        if ($convertToWebp && function_exists('imagewebp')) {
            imagepalettetotruecolor($imageResource);
            imagealphablending($imageResource, true);
            imagesavealpha($imageResource, true);
            imagewebp($imageResource, $targetFullPath, $quality);
        } elseif ($extension === 'png' || $mimeType === 'image/png') {
            imagealphablending($imageResource, false);
            imagesavealpha($imageResource, true);
            // PNG quality in GD is 0 (no compression) to 9
            $pngCompression = (int) round((100 - $quality) / 10);
            imagepng($imageResource, $targetFullPath, min(9, max(0, $pngCompression)));
        } else {
            imagejpeg($imageResource, $targetFullPath, $quality);
        }

        imagedestroy($imageResource);

        return $relativePath;
    }

    /**
     * Optimize an existing image file on disk in-place.
     *
     * @param  array{maxWidth?: int, maxHeight?: int, quality?: int}  $options
     * @return bool True if optimized successfully
     */
    public function optimizeExistingFile(string $absolutePath, array $options = []): bool
    {
        if (! file_exists($absolutePath) || ! is_file($absolutePath)) {
            return false;
        }

        $maxWidth = $options['maxWidth'] ?? 1600;
        $maxHeight = $options['maxHeight'] ?? 1600;
        $quality = $options['quality'] ?? 82;

        $mimeType = mime_content_type($absolutePath);
        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        if (! $this->isProcessableImage($mimeType, $extension)) {
            return false;
        }

        $imageResource = $this->createImageResource($absolutePath, $mimeType, $extension);
        if (! $imageResource) {
            return false;
        }

        $imageResource = $this->correctOrientation($imageResource, $absolutePath);
        $imageResource = $this->resizeIfNeeded($imageResource, $maxWidth, $maxHeight);

        $tempPath = $absolutePath.'.tmp';

        $saved = false;
        if ($extension === 'webp' && function_exists('imagewebp')) {
            imagepalettetotruecolor($imageResource);
            imagealphablending($imageResource, true);
            imagesavealpha($imageResource, true);
            $saved = imagewebp($imageResource, $tempPath, $quality);
        } elseif ($extension === 'png' || $mimeType === 'image/png') {
            imagealphablending($imageResource, false);
            imagesavealpha($imageResource, true);
            $pngCompression = (int) round((100 - $quality) / 10);
            $saved = imagepng($imageResource, $tempPath, min(9, max(0, $pngCompression)));
        } else {
            $saved = imagejpeg($imageResource, $tempPath, $quality);
        }

        imagedestroy($imageResource);

        if ($saved && file_exists($tempPath)) {
            $originalSize = filesize($absolutePath);
            $newSize = filesize($tempPath);

            // Only overwrite if the new file is actually smaller or was resized
            if ($newSize < $originalSize || $newSize > 0) {
                rename($tempPath, $absolutePath);

                return true;
            }

            @unlink($tempPath);
        }

        return false;
    }

    /**
     * Check if MIME / extension can be processed by GD.
     */
    protected function isProcessableImage(?string $mimeType, string $extension): bool
    {
        $validMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
        $validExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        return in_array($extension, $validExts, true) || in_array($mimeType, $validMimes, true);
    }

    /**
     * Create GD image resource from file path.
     *
     * @return \GdImage|resource|false
     */
    protected function createImageResource(string $path, ?string $mimeType, string $extension)
    {
        if ($extension === 'jpg' || $extension === 'jpeg' || $mimeType === 'image/jpeg') {
            return @imagecreatefromjpeg($path);
        }
        if ($extension === 'png' || $mimeType === 'image/png') {
            return @imagecreatefrompng($path);
        }
        if ($extension === 'webp' || $mimeType === 'image/webp') {
            return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false;
        }
        if ($extension === 'gif' || $mimeType === 'image/gif') {
            return @imagecreatefromgif($path);
        }

        return @imagecreatefromstring(file_get_contents($path));
    }

    /**
     * Resize image proportionally if its dimensions exceed limits.
     *
     * @param  \GdImage|resource  $src
     * @return \GdImage|resource
     */
    protected function resizeIfNeeded($src, int $maxWidth, int $maxHeight)
    {
        $origWidth = imagesx($src);
        $origHeight = imagesy($src);

        if ($origWidth <= $maxWidth && $origHeight <= $maxHeight) {
            return $src;
        }

        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = (int) round($origWidth * $ratio);
        $newHeight = (int) round($origHeight * $ratio);

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        imagedestroy($src);

        return $dst;
    }

    /**
     * Correct orientation based on EXIF data.
     *
     * @param  \GdImage|resource  $image
     * @return \GdImage|resource
     */
    protected function correctOrientation($image, string $path)
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        if (! $exif || empty($exif['Orientation'])) {
            return $image;
        }

        return match ($exif['Orientation']) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }
}
