<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel admins (dengan lokasi_id & foto final)
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->nullOnDelete();
            $table->string('nama', 50);
            $table->string('username', 20)->unique();
            $table->string('foto')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel super_admins (dengan foto final)
        Schema::create('super_admins', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('username', 20)->unique();
            $table->string('foto')->nullable();
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
