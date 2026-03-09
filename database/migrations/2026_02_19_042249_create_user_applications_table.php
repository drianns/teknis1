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
        Schema::create('user_applications', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('level_user');
            $table->string('department')->nullable();
            $table->string('group_agent')->nullable();
            $table->string('site')->nullable();
            $table->string('status')->default('Aktif');
            $table->json('channels')->nullable();
            $table->text('description')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_applications');
    }
};
