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
        Schema::create('wallet_transactions', function (Blueprint $table) {
    $table->id();

    $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();

    $table->string('type'); 
    // topup | job_fee | refund | penalty | bonus | adjustment

    $table->bigInteger('amount');

    $table->bigInteger('balance_before');
    $table->bigInteger('balance_after');

    $table->string('reference_type')->nullable();
    $table->unsignedBigInteger('reference_id')->nullable();

    $table->string('idempotency_key')->unique()->nullable();

    $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();

    $table->text('note')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
