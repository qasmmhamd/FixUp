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
        Schema::create('orders', function (Blueprint $table) {
               $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->boolean('priority')->default(false);
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
                'expired',
                'cancelled'
            ])->default('pending');
            $table->timestamp('expires_at');
            
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('career_id')->constrained()->cascadeOnDelete();


            $table->timestamp('scheduled_at')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
