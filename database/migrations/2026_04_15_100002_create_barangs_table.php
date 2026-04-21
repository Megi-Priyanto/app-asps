<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50)->unique();
            $table->string('nama_barang', 150);
            $table->foreignId('kategori_barang_id')
                ->constrained('kategori_barangs')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('lokasi_id')
                ->nullable()
                ->constrained('lokasis')
                ->nullOnDelete();
            $table->string('lokasi_simpan', 150)->nullable(); // Lokasi penyimpanan (ruang sarpras, lab, dll)
            $table->integer('jumlah')->default(0);
            $table->integer('jumlah_baik')->default(0);
            $table->integer('jumlah_rusak_ringan')->default(0);
            $table->integer('jumlah_rusak_berat')->default(0);
            $table->string('satuan', 20);
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->boolean('is_pinjaman')->default(false); // Apakah barang bisa dipinjam
            $table->date('tanggal_pengadaan');
            $table->string('sumber', 100)->nullable(); // Dari mana barang diperoleh
            $table->string('gambar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
