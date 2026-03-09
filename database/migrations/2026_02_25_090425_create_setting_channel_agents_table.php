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
        Schema::create('setting_channel_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('menu')->nullable();
            $table->string('sub_menu')->nullable();
            $table->string('detail_menu')->nullable();
            $table->text('url')->nullable();
            $table->string('status', 10)->default('Yes'); // Yes/No
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_channel_agents');
    }
};
