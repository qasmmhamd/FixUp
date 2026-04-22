<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create workers table for professional worker profiles.
 * 
 * This migration creates the workers table which stores professional profiles
 * of workers in the FixUp system. Each worker is linked to a user account and
 * belongs to a specific career category with detailed profile information.
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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
            $table->foreignId('career_id')->constrained()->cascadeOnDelete();
            $table->text('about')->nullable();
            $table->enum('status', ['active', 'blocked', 'waiting'])->default('waiting');
            $table->integer('years_experience')->nullable();
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
        Schema::dropIfExists('workers');
    }
};
