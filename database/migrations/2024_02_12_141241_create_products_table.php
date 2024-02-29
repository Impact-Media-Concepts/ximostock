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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_product_id')->nullable()->constrained('products');
            $table->string('sku')->unique()->nullable();
            $table->bigInteger('ean')->unique()->nullable();
            $table->string('title')->nullable();
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('discount')->nullable();
            $table->boolean('backorders')->default(false)->nullable();
            $table->boolean('communicate_stock')->default(true)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
