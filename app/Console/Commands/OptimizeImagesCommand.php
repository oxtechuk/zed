<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class OptimizeImagesCommand extends Command
{
    protected $signature = 'images:optimize
                            {--dir= : Specific subfolder in public storage to optimize (e.g. cars, offers)}
                            {--quality=82 : Compression quality (1-100)}
                            {--max-width=1600 : Maximum width in pixels}';

    protected $description = 'Compress, resize, and optimize existing uploaded images in storage';

    public function handle(ImageOptimizerService $optimizer): int
    {
        $this->info('🔍 جاري فحص ملفات الصور في التخزين...');

        $basePath = Storage::disk('public')->path('');
        if ($this->option('dir')) {
            $basePath = rtrim($basePath, '/\\').DIRECTORY_SEPARATOR.trim($this->option('dir'), '/\\');
        }

        if (! is_dir($basePath)) {
            $this->error("المجلد غير موجود: {$basePath}");

            return self::FAILURE;
        }

        $quality = (int) $this->option('quality');
        $maxWidth = (int) $this->option('max-width');

        $imageFiles = [];
        $validExts = ['jpg', 'jpeg', 'png', 'webp'];

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basePath, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, $validExts, true)) {
                    $imageFiles[] = $file->getRealPath();
                }
            }
        }

        $totalCount = count($imageFiles);
        if ($totalCount === 0) {
            $this->warn('لم يتم العثور على صور لمعالجتها.');

            return self::SUCCESS;
        }

        $this->info("تم العثور على {$totalCount} صورة. جاري بدء الضغط والتحسين...");

        $totalOriginalBytes = 0;
        $totalNewBytes = 0;
        $optimizedCount = 0;

        $bar = $this->output->createProgressBar($totalCount);
        $bar->start();

        foreach ($imageFiles as $filePath) {
            $origSize = filesize($filePath);
            $totalOriginalBytes += $origSize;

            $success = $optimizer->optimizeExistingFile($filePath, [
                'quality' => $quality,
                'maxWidth' => $maxWidth,
            ]);

            $newSize = filesize($filePath);
            $totalNewBytes += $newSize;

            if ($success && $newSize < $origSize) {
                $optimizedCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $savedBytes = max(0, $totalOriginalBytes - $totalNewBytes);
        $savedPercent = $totalOriginalBytes > 0 ? round(($savedBytes / $totalOriginalBytes) * 100, 1) : 0;

        $this->table(
            ['المقياس', 'القيمة'],
            [
                ['إجمالي الصور التي تم فحصها', number_format($totalCount)],
                ['الصور التي تم ضغطها وتقليل حجمها', number_format($optimizedCount)],
                ['الحجم الإجمالي قبل الضغط', round($totalOriginalBytes / 1024 / 1024, 2).' MB'],
                ['الحجم الإجمالي بعد الضغط', round($totalNewBytes / 1024 / 1024, 2).' MB'],
                ['المساحة التي تم توفيرها', round($savedBytes / 1024 / 1024, 2)." MB ({$savedPercent}%)"],
            ]
        );

        $this->info('✅ اكتملت عملية تحسين وضغط الصور بنجاح!');

        return self::SUCCESS;
    }
}
