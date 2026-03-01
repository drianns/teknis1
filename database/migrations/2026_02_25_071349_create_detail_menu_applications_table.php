<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_menu_applications', function (Blueprint $table) {
            $table->id();
            $table->string('menu_name');
            $table->string('sub_menu_name')->nullable();
            $table->string('detail_menu_name');
            $table->string('url')->nullable();
            $table->enum('type', ['Yes', 'No'])->default('No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_menu_applications');
    }
};
