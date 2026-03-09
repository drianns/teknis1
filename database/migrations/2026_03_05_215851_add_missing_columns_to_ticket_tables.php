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
        Schema::table('chat_header_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_header_tickets', 'km_article_id')) {
                $table->unsignedBigInteger('km_article_id')->nullable()->after('user_agent_id');
            }
            if (!Schema::hasColumn('chat_header_tickets', 'need_escalated')) {
                $table->boolean('need_escalated')->default(false)->after('answer');
            }
            if (!Schema::hasColumn('chat_header_tickets', 'source_type')) {
                $table->string('source_type')->nullable()->after('need_escalated');
            }
        });

        Schema::table('result_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('result_tickets', 'genesisnumber')) {
                $table->string('genesisnumber')->nullable()->after('company_id');
            }
            if (!Schema::hasColumn('result_tickets', 'channel_id')) {
                $table->unsignedBigInteger('channel_id')->nullable()->after('user_agent_id');
            }
            if (!Schema::hasColumn('result_tickets', 'km_article_id')) {
                $table->unsignedBigInteger('km_article_id')->nullable()->after('channel_id');
            }
            if (!Schema::hasColumn('result_tickets', 'priority')) {
                $table->string('priority')->nullable()->after('km_article_id');
            }
            if (!Schema::hasColumn('result_tickets', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('flaging');
            }
            if (!Schema::hasColumn('result_tickets', 'subcategory_id')) {
                $table->unsignedBigInteger('subcategory_id')->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('result_tickets', 'blast_queues_id')) {
                $table->unsignedBigInteger('blast_queues_id')->nullable()->after('subcategory_id');
            }
            if (!Schema::hasColumn('result_tickets', 'ticket_position')) {
                $table->string('ticket_position')->nullable()->after('ticket_number');
            }
            if (!Schema::hasColumn('result_tickets', 'is_merged')) {
                $table->boolean('is_merged')->default(false)->after('payload');
            }
            if (!Schema::hasColumn('result_tickets', 'parent_merge_id')) {
                $table->unsignedBigInteger('parent_merge_id')->nullable()->after('is_merged');
            }
            if (!Schema::hasColumn('result_tickets', 'merged_at')) {
                $table->timestamp('merged_at')->nullable()->after('parent_merge_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_header_tickets', function (Blueprint $table) {
            $table->dropColumn(['km_article_id', 'need_escalated', 'source_type']);
        });

        Schema::table('result_tickets', function (Blueprint $table) {
            $table->dropColumn([
                'genesisnumber', 'channel_id', 'km_article_id', 'priority', 
                'category_id', 'subcategory_id', 'blast_queues_id', 
                'ticket_position', 'is_merged', 'parent_merge_id', 'merged_at'
            ]);
        });
    }
};
