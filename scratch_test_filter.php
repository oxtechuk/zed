<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(App\Services\Api\Store\CarApiService::class);

// 1. Just offer_id = 10
$carsOffer10 = $service->list(['offer_id' => 10]);
echo "Cars in offer 10: " . $carsOffer10->total() . "\n";
foreach ($carsOffer10 as $c) {
    echo "  Car: {$c->name}, model={$c->model}, car_model_id={$c->car_model_id}\n";
}

// 2. offer_id = 10 and model_id = 1
$carsFiltered = $service->list(['offer_id' => 10, 'model_id' => 1]);
echo "Cars in offer 10 with model_id=1: " . $carsFiltered->total() . "\n";
