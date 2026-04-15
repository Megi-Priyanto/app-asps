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
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('password');
        });

        Schema::table('gurus', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('password');
        });

        Schema::table('pegawais', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
