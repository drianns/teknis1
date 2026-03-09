<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // File ini dibuat otomatis oleh artisan - tabelnya sudah ada di DB
    // Migration ini sudah tercatat di tabel migrations, jadi skip saja
    public function up(): void
    {
        // Tabel sudah dibuat oleh migration sebelumnya, tidak perlu re-create
        if (Schema::hasTable('login_activities')) {
            return;
        }

        Schema::create('login_activities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Tidak drop karena schema asli sudah di file 2026_03_06_000001
    }
};
