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
        Schema::create('group_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('company_id');
            $table->timestamps();
        });

        Schema::create('group_route_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_route_id');
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_route_agents');
        Schema::dropIfExists('group_routes');
    }
};
