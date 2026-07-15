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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('property_name');
            $table->string('property_type'); // apartment, house, condo, etc.
            $table->text('address');
            $table->decimal('rent_amount', 8, 2);
            $table->string('currency', 3)->default('MYR');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->text('description')->nullable();
            //$table->string('contact_name')->nullable();
            //$table->string('contact_phone')->nullable();
            //$table->string('contact_email')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
