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
        Schema::create('data_access_applications', function (Blueprint $table) {
            $table->id();
            $table->string('level_user')->nullable();
            $table->string('menu_level1')->nullable();
            $table->string('menu_level2')->nullable();
            $table->string('menu_level3')->nullable();
            $table->text('description')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_access_applications');
    }
};
