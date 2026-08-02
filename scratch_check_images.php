<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Car;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Storage;

echo 'storage url: '.Storage::disk('public')->url('images/car1.png')."\n";
echo 'car count: '.Car::count()."\n";
$car = Car::first();
var_dump($car ? $car->getAttributes() : null);
echo 'cast thumbnail (web): '.($car->thumbnail ?? 'NULL')."\n";
