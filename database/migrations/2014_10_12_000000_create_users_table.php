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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_space_id')->nullable()->constrained('work_spaces')->cascadeOnDelete();
            $table->string('name');
            $table->enum('role', ['admin', 'manager', 'supplier'])->default('manager');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('primary_color')->default('#3DABD5');
            $table->string('secondary_color')->default('#F0F0F0');
            $table->string('background_color')->default('#FFFFFF');
            $table->string('text_color')->default('#717171');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
