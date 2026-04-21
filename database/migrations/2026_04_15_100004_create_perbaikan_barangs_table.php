<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perbaikan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_perbaikan', 50)->unique();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('peminjaman_id')
                ->nullable()
                ->constrained('peminjaman_barangs')
                ->onDelete('set null');

            $table->integer('jumlah_rusak')->default(1);
            $table->enum('tingkat_kerusakan', ['Rusak Ringan', 'Rusak Berat']);
            $table->text('keterangan_kerusakan')->nullable();

            $table->date('tanggal_masuk');
            $table->date('tanggal_selesai')->nullable();

            $table->enum('status', ['Menunggu', 'Dalam Perbaikan', 'Selesai'])->default('Menunggu');
            $table->text('catatan_perbaikan')->nullable();
            $table->string('foto_nota')->nullable();
            $table->decimal('biaya_perbaikan', 15, 2)->nullable();

            // Admin yang menangani perbaikan
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perbaikan_barangs');
    }
};
