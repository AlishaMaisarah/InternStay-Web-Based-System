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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Notification Settings
            $table->enum('notification_frequency', ['instant', 'daily', 'weekly', 'off'])->default('daily');
            $table->boolean('notify_internships')->default(true);
            $table->boolean('notify_rentals')->default(true);
            
            // Internship Preferences (JSON arrays)
            $table->json('preferred_industries')->nullable();
            $table->json('preferred_internship_locations')->nullable();
            
            // Rental Preferences (JSON arrays)
            $table->json('preferred_property_types')->nullable();
            $table->json('preferred_rental_states')->nullable();
            $table->decimal('max_rental_price', 10, 2)->nullable();
            
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
