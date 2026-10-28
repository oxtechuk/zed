<?php

namespace App\Console\Commands;

use App\Services\Seo\IndexNowService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class IndexNowSubmitCommand extends Command
{
    protected $signature = 'seo:indexnow
                            {urls?* : Absolute URLs to submit}
                            {--sitemap : Submit every URL in https://zad-capital.sa/sitemap.xml}';

    protected $description = 'Notify IndexNow search engines (Bing, Yandex, …) about new or changed URLs';

    public function handle(IndexNowService $indexNow): int
    {
        $urls = $this->argument('urls');

        if ($this->option('sitemap')) {
            $xml = Http::timeout(20)->get('https://zad-capital.sa/sitemap.xml')->body();
            preg_match_all('#<loc>([^<]+)</loc>#', $xml, $matches);
            $urls = array_merge($urls, array_map('trim', $matches[1]));
        }

        if ($urls === []) {
            $this->error('Nothing to submit. Pass URLs or --sitemap.');

            return self::FAILURE;
        }

        $status = $indexNow->submit($urls);
        $this->info('Submitted '.count($urls)." URL(s) → HTTP {$status}");

        return in_array($status, [200, 202], true) ? self::SUCCESS : self::FAILURE;
    }
}
