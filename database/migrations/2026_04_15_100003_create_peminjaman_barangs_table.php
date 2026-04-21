<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi', 50)->unique();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');

            // Polymorphic: bisa Guru, Siswa, atau Pegawai
            $table->string('borrower_type');
            $table->unsignedBigInteger('borrower_id');

            $table->integer('jumlah_pinjam')->default(1);
            $table->dateTime('tanggal_pinjam');
            $table->dateTime('tanggal_kembali_rencana');
            $table->dateTime('tanggal_kembali_aktual')->nullable();

            $table->enum('status', [
                'Menunggu',         // Sudah request, belum di-ACC
                'Disetujui',        // Di-ACC admin, tapi belum ambil
                'Sedang Dipinjam',  // Sudah diambil
                'Sudah Dikembalikan',
                'Terlambat',
                'Ditolak',
            ])->default('Menunggu');

            $table->text('keperluan')->nullable();
            $table->enum('kondisi_barang', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();

            // Admin (petugas) yang memproses
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->text('catatan_admin')->nullable();

            // Kolom identitas peminjam yang diinput manual oleh Admin
            $table->string('nama_peminjam')->nullable();
            $table->enum('peran_peminjam', ['Siswa', 'Guru', 'Pegawai'])->nullable();
            $table->string('detail_peminjam')->nullable(); // kelas (siswa) atau jabatan (guru/pegawai)

            $table->timestamps();

            // Index untuk polymorphic
            $table->index(['borrower_type', 'borrower_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barangs');
    }
};
