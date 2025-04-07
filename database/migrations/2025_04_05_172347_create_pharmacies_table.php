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
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('registration_number')->unique();
            $table->string('license_details');
            $table->text('address');
            $table->string('phone');
            $table->string('city');
            $table->boolean('is_blocked')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->string('profile_image')->nullable();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
