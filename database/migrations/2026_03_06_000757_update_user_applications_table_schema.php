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
        Schema::table('user_applications', function (Blueprint $table) {
            $table->string('user_name')->unique()->after('id');
            $table->string('name')->after('user_name');
            $table->string('email')->unique()->after('name');
            $table->string('password')->after('email');
            $table->string('level_user')->after('password');
            $table->string('department')->nullable()->after('level_user');
            $table->string('group_agent')->nullable()->after('department');
            $table->string('site')->nullable()->after('group_agent');
            $table->string('status')->default('Aktif')->after('site');
            $table->json('channels')->nullable()->after('status');
            $table->text('description')->nullable()->after('channels');
            $table->string('photo_url')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_applications', function (Blueprint $table) {
            $table->dropColumn([
                'user_name', 'name', 'email', 'password', 'level_user', 
                'department', 'group_agent', 'site', 'status', 
                'channels', 'description', 'photo_url'
            ]);
        });
    }
};
