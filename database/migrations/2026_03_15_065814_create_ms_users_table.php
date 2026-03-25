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
    Schema::create('msuser', function (Blueprint $table) {
        $table->id('USERID'); // Samakan dengan SQL (Primary Key)
        $table->string('USERNAME', 50)->nullable();
        $table->string('NAME', 150)->nullable();
        $table->string('PASSWORD')->nullable();
        $table->string('LEVELUSER', 50)->nullable();
        $table->string('EMAIL_ADDRESS', 150)->nullable();
        $table->string('ORGANIZATION_NAME', 150)->nullable();
        $table->string('Status', 20)->default('Aktif');
        $table->string('NA', 2)->default('Y'); // Penting untuk filter data aktif
        $table->datetime('DATECREATE')->nullable();
        $table->string('Description')->nullable();
        $table->timestamps(); // Tetap pakai ini untuk tracking di Laravel
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msuser');
    }
};
