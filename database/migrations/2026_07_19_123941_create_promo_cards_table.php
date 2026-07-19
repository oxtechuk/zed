<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promo_cards', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('medium'); // large | medium | small
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->string('image');
            $table->json('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->json('badge')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_cards');
    }
};
