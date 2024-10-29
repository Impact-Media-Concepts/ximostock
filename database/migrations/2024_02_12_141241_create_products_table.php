<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\EnumProductTypeHelper;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Retrieve the enum values from the model
        $enumValues = EnumProductTypeHelper::getEnumValuesFromProduct(Product::class, 'type');
        log::info('Migration');
        Log::info($enumValues);
        // Ensure enum values were retrieved correctly
        if (empty($enumValues)) {
            throw new InvalidArgumentException('Enum values for Product Type type could not be retrieved.');
        }

        Schema::create('products', function (Blueprint $table) use ($enumValues) {
            $table->id();
            $table->foreignId('work_space_id')->constrained();
            $table->foreignId('parent_product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->enum('type', [$enumValues])->required();
            $table->string('sku')->unique()->nullable();
            $table->bigInteger('ean')->unique()->nullable();
            $table->string('title')->nullable();
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('discount')->nullable();
            $table->boolean('backorders')->default(false)->required();
            $table->bigInteger('stock_quantity')->default(0)->required();
            $table->boolean('status')->default(false)->required();
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
