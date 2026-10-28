<?php

declare(strict_types=1);

namespace App\Services\Seo;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * IndexNow (https://www.indexnow.org): tells Bing, Yandex, Naver, Seznam… right away
 * that a URL was added or changed. Google doesn't use IndexNow (it reads the sitemap).
 * The key is public by design; it must match public/{KEY}.txt.
 */
final class IndexNowService
{
    public const KEY = '80234b6692340eafdb59cf925d102bc0';

    private const HOST = 'zad-capital.sa';

    private const ENDPOINT = 'https://api.indexnow.org/indexnow';

    /** Submit absolute URLs (up to 10,000 per call). Returns the HTTP status, or 0 on failure. */
    public function submit(array $urls): int
    {
        $urls = array_values(array_unique(array_filter($urls, fn ($url) => is_string($url) && str_starts_with($url, 'https://'.self::HOST.'/'))));
        if ($urls === []) {
            return 0;
        }

        try {
            $status = Http::timeout(10)->asJson()->post(self::ENDPOINT, [
                'host' => self::HOST,
                'key' => self::KEY,
                'keyLocation' => 'https://'.self::HOST.'/'.self::KEY.'.txt',
                'urlList' => array_slice($urls, 0, 10000),
            ])->status();
        } catch (\Throwable $e) {
            Log::warning('IndexNow submit failed', ['error' => $e->getMessage(), 'count' => count($urls)]);

            return 0;
        }

        Log::info('IndexNow submitted', ['status' => $status, 'count' => count($urls)]);

        return $status;
    }

    /** Notify after the response is sent, so saving in the admin is never slowed down or broken. */
    public static function notifyLater(string $path): void
    {
        if (! str_contains((string) config('app.url'), self::HOST)) {
            return;
        }

        $url = 'https://'.self::HOST.'/'.implode('/', array_map('rawurlencode', explode('/', trim($path, '/'))));
        dispatch(fn () => app(self::class)->submit([$url]))->afterResponse();
    }
}
