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
        Schema::table('auto_reply_templates', function (Blueprint $table) {
            $table->string('account_email')->nullable()->after('id');
            $table->boolean('is_active')->default(true)->after('account_email');
            $table->string('name')->nullable()->change();
            $table->string('subject')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auto_reply_templates', function (Blueprint $table) {
            $table->dropColumn(['account_email', 'is_active']);
            $table->string('name')->nullable(false)->change();
            $table->string('subject')->nullable(false)->change();
        });
    }
};
