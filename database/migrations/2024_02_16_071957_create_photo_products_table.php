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
        Schema::create('photo_product', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Photo::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Product::class)->constrained()->cascadeOnDelete();
            $table->boolean('primary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo__products');
    }
};
