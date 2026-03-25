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
        Schema::create('ms_level_user', function (Blueprint $table) {
            $table->id('LevelUserID');
            $table->string('Name')->nullable();
            $table->string('Description')->nullable();
            $table->string('NA')->default('N')->nullable();
            $table->string('EscalationIdentity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_level_user');
    }
};
