<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('agent_aux_logs')) {
            Schema::create('agent_aux_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('username');
                $table->string('description');
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->timestamps();
            });
        } else {
            Schema::table('agent_aux_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('agent_aux_logs', 'company_id')) {
                    $table->unsignedBigInteger('company_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('agent_aux_logs', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('company_id');
                }
                if (!Schema::hasColumn('agent_aux_logs', 'username')) {
                    $table->string('username')->after('user_id');
                }
                if (!Schema::hasColumn('agent_aux_logs', 'description')) {
                    $table->string('description')->after('username');
                }
                if (!Schema::hasColumn('agent_aux_logs', 'start_time')) {
                    $table->dateTime('start_time')->after('description');
                }
                if (!Schema::hasColumn('agent_aux_logs', 'end_time')) {
                    $table->dateTime('end_time')->after('start_time');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_aux_logs');
    }
};
