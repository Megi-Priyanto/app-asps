<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_pengaduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->cascadeOnDelete();
            $table->string('reporter_type')->nullable();
            $table->unsignedBigInteger('reporter_id')->nullable();
            $table->index(['reporter_type', 'reporter_id'], 'laporan_pengaduans_reporter_type_reporter_id_index');
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
            $table->text('ket');
            $table->string('lokasi');
            $table->string('foto')->nullable();
            $table->boolean('is_anonim')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pengaduans');
    }
};
