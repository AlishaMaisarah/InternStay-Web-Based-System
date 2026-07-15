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
        Schema::table('rentals', function (Blueprint $table) {
            if (Schema::hasColumn('rentals', 'city')) {
                $table->dropColumn('city');
            }

            if (Schema::hasColumn('rentals', 'state')) {
                $table->dropColumn('state');
            }

            if (Schema::hasColumn('rentals', 'postal_code')) {
                $table->dropColumn('postal_code');
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('city');
            $table->string('state');
            $table->string('postal_code')->nullable();
        });
    }
};
