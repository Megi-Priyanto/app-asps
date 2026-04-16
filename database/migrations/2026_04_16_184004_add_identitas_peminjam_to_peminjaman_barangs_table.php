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
        Schema::table('peminjaman_barangs', function (Blueprint $table) {
            // Kolom identitas peminjam yang diinput manual oleh Admin
            $table->string('nama_peminjam')->nullable()->after('catatan_admin');
            $table->enum('peran_peminjam', ['Siswa', 'Guru', 'Pegawai'])->nullable()->after('nama_peminjam');
            $table->string('detail_peminjam')->nullable()->after('peran_peminjam'); // kelas (siswa) atau jabatan (guru/pegawai)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_barangs', function (Blueprint $table) {
            $table->dropColumn(['nama_peminjam', 'peran_peminjam', 'detail_peminjam']);
        });
    }
};
