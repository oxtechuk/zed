<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Car;
use App\Models\CarImage;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\PromoBanner;
use App\Models\PromoCard;
use App\Services\ImageOptimizerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImagesCommand extends Command
{
    protected $signature = 'images:optimize-existing {--min-size=300 : Minimum size in KB to convert}';
    protected $description = 'Convert existing images over 300KB to WebP and update database records';

    public function handle(ImageOptimizerService $optimizer): int
    {
        $minSizeKb = (int) $this->option('min-size');
        $minSizeBytes = $minSizeKb * 1024;
        $disk = Storage::disk('public');

        $this->info("Scanning storage disk for images larger than {$minSizeKb} KB...");

        $files = $disk->allFiles();
        $convertedCount = 0;
        $savedBytes = 0;

        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (! in_array($ext, ['png', 'jpg', 'jpeg'])) {
                continue;
            }

            if (str_starts_with($file, 'backup_originals/')) {
                continue;
            }

            $size = $disk->size($file);
            if ($size < $minSizeBytes) {
                continue;
            }

            $this->line("Processing [".number_format($size / 1024, 1)." KB]: {$file}");

            $content = $disk->get($file);
            $webpContent = $optimizer->convertToWebp($content);

            if (! $webpContent) {
                $this->warn("  -> Failed to convert: {$file}");
                continue;
            }

            $newSize = strlen($webpContent);
            if ($newSize >= $size) {
                $this->line("  -> WebP not smaller, skipping.");
                continue;
            }

            // 1. Backup original
            $backupPath = 'backup_originals/' . $file;
            $disk->put($backupPath, $content);

            // 2. Save WebP file
            $newPath = preg_replace('/\.(png|jpe?g)$/i', '.webp', $file);
            $disk->put($newPath, $webpContent);

            // 3. Update database references
            $this->updateDatabaseReferences($file, $newPath);

            // 4. Delete old file if path changed
            if ($newPath !== $file) {
                $disk->delete($file);
            }

            $savedBytes += ($size - $newSize);
            $convertedCount++;
            $this->info("  -> Converted to {$newPath} [".number_format($newSize / 1024, 1)." KB] - Saved ".number_format(($size - $newSize) / 1024, 1)." KB");
        }

        $this->info("Optimization complete! Converted {$convertedCount} images. Total bandwidth saved: ".number_format($savedBytes / (1024 * 1024), 2)." MB.");

        return self::SUCCESS;
    }

    protected function updateDatabaseReferences(string $oldPath, string $newPath): void
    {
        HomeSection::where('image', $oldPath)->update(['image' => $newPath]);
        HomeSection::where('background_image', $oldPath)->update(['background_image' => $newPath]);

        HeroSlide::where('image', $oldPath)->update(['image' => $newPath]);
        HeroSlide::where('mobile_image', $oldPath)->update(['mobile_image' => $newPath]);

        Car::where('thumbnail', $oldPath)->update(['thumbnail' => $newPath]);
        CarImage::where('path', $oldPath)->update(['path' => $newPath]);

        PromoBanner::where('image', $oldPath)->update(['image' => $newPath]);
        PromoCard::where('image', $oldPath)->update(['image' => $newPath]);
    }
}
