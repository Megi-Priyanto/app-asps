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
        Schema::create('komentar_laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan_pengaduans')->cascadeOnDelete();
            $table->string('sender_type'); // 'admin' atau 'siswa'
            $table->unsignedBigInteger('sender_id'); // ID dari admin atau siswa
            $table->text('pesan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar_laporans');
    }
};
