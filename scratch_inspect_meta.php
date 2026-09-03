<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$carService = app(App\Services\Api\Store\CarApiService::class);
$meta = $carService->listMeta();
echo "filterModels count: " . count($meta['filterModels']) . "\n";
foreach (array_slice($meta['filterModels']->toArray(), 0, 10) as $m) {
    echo json_encode($m) . "\n";
}
