<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->string('source_url')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropColumn('source_url');
        });
    }

};
