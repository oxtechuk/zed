<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_slug_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('old_slug')->index();
            $table->string('new_slug');
            $table->foreignId('car_id')->nullable()->constrained('cars')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_slug_redirects');
    }
};
