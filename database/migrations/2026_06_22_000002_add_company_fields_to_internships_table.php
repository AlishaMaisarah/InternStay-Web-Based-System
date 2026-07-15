<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->boolean('is_suspended')->default(false);
            $table->string('contact_person')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'is_suspended', 'contact_person']);
        });
    }
};
