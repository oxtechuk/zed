<?php

namespace App\Console\Commands;

use App\Models\SeoEntry;
use App\Services\Seo\SeoEntryResolver;
use Illuminate\Console\Command;

class SeoImportCommand extends Command
{
    protected $signature = 'seo:import
                            {file : CSV with columns path, type, focus_keyword, meta_title, meta_description, intro, faq (JSON [{q,a}]), image_alt}
                            {--prune : Delete entries whose path is not in the file}
                            {--dry-run : Show what would change without saving}';

    protected $description = 'Import page titles, extra text and FAQ (seo_entries) from the SEO team\'s CSV';

    public function handle(): int
    {
        $file = $this->argument('file');
        if (! is_readable($file) || ! ($handle = fopen($file, 'r'))) {
            $this->error("Cannot read {$file}");

            return self::FAILURE;
        }

        $header = fgetcsv($handle);
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);
        $fields = ['type', 'focus_keyword', 'meta_title', 'meta_description', 'intro', 'faq', 'image_alt'];

        $seen = [];
        $created = $updated = $unchanged = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, array_pad($row, count($header), ''));
            $path = SeoEntryResolver::normalize((string) ($data['path'] ?? ''));
            if ($path === '/' && trim((string) ($data['path'] ?? '')) === '') {
                continue;
            }
            $seen[] = $path;

            $values = [];
            foreach ($fields as $field) {
                $value = trim((string) ($data[$field] ?? ''));
                $values[$field] = $value === '' ? null : $value;
            }
            if ($values['faq'] !== null) {
                $faq = json_decode($values['faq'], true);
                if (! is_array($faq)) {
                    $this->warn("{$path}: invalid FAQ JSON, skipped");

                    continue;
                }
                $values['faq'] = $faq;
            }

            $entry = SeoEntry::firstOrNew(['path' => $path]);
            $entry->fill($values);
            if (! $entry->exists) {
                $created++;
            } elseif ($entry->isDirty()) {
                $updated++;
            } else {
                $unchanged++;

                continue;
            }
            if (! $this->option('dry-run')) {
                $entry->save();
            }
        }
        fclose($handle);

        $pruned = 0;
        if ($this->option('prune')) {
            $stale = SeoEntry::whereNotIn('path', $seen);
            $pruned = $stale->count();
            if (! $this->option('dry-run')) {
                $stale->delete();
            }
        }

        $this->info(($this->option('dry-run') ? '[dry-run] ' : '')."created {$created}, updated {$updated}, unchanged {$unchanged}, pruned {$pruned}");

        return self::SUCCESS;
    }
}
