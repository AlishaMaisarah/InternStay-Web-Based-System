<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->string('internship_name');
            $table->string('company');
            $table->string('industry')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            //$table->string('contact_email')->nullable();
            //$table->string('contact_phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
