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
        Schema::table('perbaikan_barangs', function (Blueprint $table) {
            $table->string('foto_nota')->nullable()->after('catatan_perbaikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perbaikan_barangs', function (Blueprint $table) {
            $table->dropColumn('foto_nota');
        });
    }
};
