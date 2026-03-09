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
        Schema::table('data_categories', function (Blueprint $table) {
            $table->foreignId('data_brand_name_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->foreignId('data_type_id')->nullable()->after('data_brand_name_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_categories', function (Blueprint $table) {
            $table->dropForeign(['data_brand_name_id']);
            $table->dropColumn('data_brand_name_id');
            $table->dropForeign(['data_type_id']);
            $table->dropColumn('data_type_id');
        });
    }
};
