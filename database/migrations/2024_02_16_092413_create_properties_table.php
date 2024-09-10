<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\EnumPropertyHelper;
use App\Models\Property;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Retrieve the enum values from the model
        $enumValues = EnumPropertyHelper::getEnumValuesFromProperty(Property::class, 'type');
        log::info('Migration');
        Log::info($enumValues);
        // Ensure enum values were retrieved correctly
        if (empty($enumValues)) {
            throw new InvalidArgumentException('Enum values for Property type could not be retrieved.');
        }

        Schema::create('properties', function (Blueprint $table) use ($enumValues) {
            $table->id();
            $table->foreignId('work_space_id')->constrained('work_spaces')->cascadeOnDelete();
            $table->string('name');
            $table->json('options')->nullable();
            $table->enum('type', [$enumValues]);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
