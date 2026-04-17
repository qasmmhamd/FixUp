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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
     $table->foreignId('worker_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('gallery_work_id')->nullable()->constrained()->nullOnDelete();
    $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
