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
        Schema::create('epic_configs', function (Blueprint $table) {
            $table->id();
            $table->string('aes')->nullable();
            $table->string('aes_user')->nullable();
            $table->string('aes_pass')->nullable();
            $table->string('port')->nullable();
            $table->string('ip_db')->nullable();
            $table->string('db_user')->nullable();
            $table->string('db_pass')->nullable();
            $table->string('db_name')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('call_history')->nullable();
            $table->string('agent_ep')->nullable();
            $table->string('inbound_ep')->nullable();
            $table->string('outbound_ep')->nullable();
            $table->string('browser_path')->nullable();
            $table->string('theme')->nullable();
            $table->string('acw')->nullable();
            $table->string('pbx_login')->nullable();
            $table->string('pbx_logout')->nullable();
            $table->string('pbx_aux')->nullable();
            $table->string('pbx_autoin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epic_configs');
    }
};
