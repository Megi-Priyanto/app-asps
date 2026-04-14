<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel admins (dengan kategori_id & panjang kolom final)
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->onDelete('set null');
            $table->string('nama', 50);
            $table->string('username', 20)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel super_admins (dengan panjang kolom final)
        Schema::create('super_admins', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('username', 20)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
        Schema::dropIfExists('super_admins');
    }
};
