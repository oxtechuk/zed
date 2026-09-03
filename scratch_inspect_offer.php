<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Car;
use App\Models\CarModel;

echo "Total cars: " . Car::count() . "\n";
echo "Cars with car_model_id NOT null: " . Car::whereNotNull('car_model_id')->count() . "\n";
echo "Cars with car_model_id NULL: " . Car::whereNull('car_model_id')->count() . "\n";
echo "Total CarModel rows: " . CarModel::count() . "\n";
$models = CarModel::take(5)->get();
foreach ($models as $m) {
    echo "Model {$m->id}: name=" . json_encode($m->name) . ", brand_id={$m->brand_id}\n";
}
