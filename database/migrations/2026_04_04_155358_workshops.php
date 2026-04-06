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
             Schema::create('workshops', function (Blueprint $table) {
                 $table->id();
                 $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
                 $table->string('name');
                 $table->boolean('is_verified')->default(false);
                 $table->text('description')->nullable();
                 $table->string('address');
                 $table->decimal('lat', 10, 8)->nullable();
                 $table->decimal('lng', 11, 8)->nullable();
                 $table->float('rating_avg')->default(0);
                 $table->timestamps();
                 $table->foreignId('workshop_id')->constrained()->cascadeOnDelete();
                 $table->foreignId('category_id')->constrained()->cascadeOnDelete();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
