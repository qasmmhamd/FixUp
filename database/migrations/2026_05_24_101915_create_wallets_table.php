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
       Schema::create('wallets', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

    $table->bigInteger('balance')->default(0);
    $table->bigInteger('total_charged')->default(0);
    $table->bigInteger('total_spent')->default(0);

    $table->enum('status', ['active', 'suspended'])->default('active');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
