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
        Schema::create('aspirasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')
            ->constrained('laporan_pengaduans')
            ->cascadeOnDelete();
            $table->foreignId('admin_id')
            ->constrained('admins')
            ->cascadeOnDelete();
            $table->enum('status', ['menunggu', 'proses', 'selesai'])->default('menunggu');
            $table->tinyInteger('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};
