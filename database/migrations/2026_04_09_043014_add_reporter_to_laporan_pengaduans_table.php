<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pengaduans', function (Blueprint $table) {
            // Tambah kolom polymorphic
            $table->string('reporter_type')->nullable()->after('siswa_id');
            $table->unsignedBigInteger('reporter_id')->nullable()->after('reporter_type');
            $index_name = 'laporan_pengaduans_reporter_type_reporter_id_index';
            $table->index(['reporter_type', 'reporter_id'], $index_name);

            // Buat siswa_id nullable (tidak wajib lagi)
            $table->unsignedBigInteger('siswa_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pengaduans', function (Blueprint $table) {
            $table->dropIndex('laporan_pengaduans_reporter_type_reporter_id_index');
            $table->dropColumn(['reporter_type', 'reporter_id']);
            $table->unsignedBigInteger('siswa_id')->nullable(false)->change();
        });
    }
};