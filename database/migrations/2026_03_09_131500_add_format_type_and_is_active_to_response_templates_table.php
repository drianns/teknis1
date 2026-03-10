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
        Schema::table('response_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('response_templates', 'format_type')) {
                $table->string('format_type')->nullable()->after('body');
            }
            if (!Schema::hasColumn('response_templates', 'is_active')) {
                $table->boolean('is_active')->default(1)->after('format_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('response_templates', function (Blueprint $table) {
            $table->dropColumn(['format_type', 'is_active']);
        });
    }
};
