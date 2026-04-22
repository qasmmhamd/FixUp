<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create area_addresses table for geographical service areas.
 * 
 * This migration creates the area_addresses table which stores different
 * geographical areas where services are available in the FixUp system. Each area
 * represents a specific location that workers can serve and customers can search within.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up(): void
    {
        Schema::create('area_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('area_name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * 
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('area_addresses');
    }
};
