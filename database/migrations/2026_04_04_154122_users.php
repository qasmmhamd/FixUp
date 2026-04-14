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
        Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('phone_number')->nullable();
                $table->string('profile_image')->nullable();
                $table->integer('age')->nullable();
                $table->date('birth_date')->nullable();
                $table->enum('role', [ 'customer', 'worker','admin'])->default('customer');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'profile_picture', 'role', 'birth_date']);
        });
    }
};
