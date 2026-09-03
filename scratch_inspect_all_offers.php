<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Offer;

$offers = Offer::with('cars')->get();
echo "Total offers: " . $offers->count() . "\n";
foreach ($offers as $o) {
    echo "Offer {$o->id}: tag={$o->tag}, title=" . json_encode($o->title) . ", cars count: " . $o->cars->count() . "\n";
    foreach ($o->cars as $c) {
        echo "   -> Car {$c->id}: {$c->name} (model: {$c->model}, car_model_id: {$c->car_model_id})\n";
    }
}
