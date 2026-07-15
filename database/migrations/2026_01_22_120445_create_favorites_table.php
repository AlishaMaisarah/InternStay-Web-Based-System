<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // polymorphic: favoritable_id + favoritable_type
            $table->morphs('favoritable');

            $table->timestamps();

            // Prevent duplicate favorites for same user + item
            $table->unique(['user_id', 'favoritable_id', 'favoritable_type'], 'favorites_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
