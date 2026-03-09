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
        // 1. data_brand_categories
        Schema::table('data_brand_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('data_brand_categories', 'data_group_name_id')) {
                $table->foreignId('data_group_name_id')->nullable()->after('id')->constrained('data_group_names')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_brand_categories', 'status')) {
                $table->string('status')->default('Aktif')->after('name');
            }
        });

        // 2. data_brand_names
        Schema::table('data_brand_names', function (Blueprint $table) {
            if (!Schema::hasColumn('data_brand_names', 'data_brand_category_id')) {
                $table->foreignId('data_brand_category_id')->nullable()->after('id')->constrained('data_brand_categories')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_brand_names', 'status')) {
                $table->string('status')->default('Aktif')->after('name');
            }
        });

        // 3. data_types
        Schema::table('data_types', function (Blueprint $table) {
            if (!Schema::hasColumn('data_types', 'status')) {
                $table->string('status')->default('Aktif')->after('name');
            }
        });

        // 4. data_categories
        Schema::table('data_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('data_categories', 'status')) {
                $table->string('status')->default('Aktif')->after('name');
            }
        });

        // 5. data_sub_categories
        Schema::table('data_sub_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('data_sub_categories', 'data_brand_name_id')) {
                $table->foreignId('data_brand_name_id')->nullable()->after('id')->constrained('data_brand_names')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_sub_categories', 'data_type_id')) {
                $table->foreignId('data_type_id')->nullable()->after('data_brand_name_id')->constrained('data_types')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_sub_categories', 'data_category_id')) {
                $table->foreignId('data_category_id')->nullable()->after('data_type_id')->constrained('data_categories')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_sub_categories', 'data_meta_id')) {
                $table->foreignId('data_meta_id')->nullable()->after('data_category_id')->constrained('data_metas')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_sub_categories', 'department_escalation_unit_id')) {
                $table->foreignId('department_escalation_unit_id')->nullable()->after('data_meta_id')->constrained('department_escalation_units')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_sub_categories', 'escalation_layer')) {
                $table->string('escalation_layer')->nullable()->after('department_escalation_unit_id');
            }
            if (!Schema::hasColumn('data_sub_categories', 'sla')) {
                $table->integer('sla')->default(0)->after('escalation_layer');
            }
            if (!Schema::hasColumn('data_sub_categories', 'status')) {
                $table->string('status')->default('Aktif')->after('sla');
            }
            if (!Schema::hasColumn('data_sub_categories', 'created_by')) {
                $table->string('created_by')->nullable()->after('status');
            }
        });

        // 6. data_metas
        Schema::table('data_metas', function (Blueprint $table) {
            if (!Schema::hasColumn('data_metas', 'data_brand_name_id')) {
                $table->foreignId('data_brand_name_id')->nullable()->after('id')->constrained('data_brand_names')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_metas', 'data_type_id')) {
                $table->foreignId('data_type_id')->nullable()->after('data_brand_name_id')->constrained('data_types')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_metas', 'data_category_id')) {
                $table->foreignId('data_category_id')->nullable()->after('data_type_id')->constrained('data_categories')->onDelete('set null');
            }
            if (!Schema::hasColumn('data_metas', 'status')) {
                $table->string('status')->default('Aktif')->after('name');
            }
            if (!Schema::hasColumn('data_metas', 'created_by')) {
                $table->string('created_by')->nullable()->after('status');
            }
        });

        // 7. data_fulfillment_locations
        Schema::table('data_fulfillment_locations', function (Blueprint $table) {
            if (!Schema::hasColumn('data_fulfillment_locations', 'status')) {
                $table->string('status')->default('Aktif')->after('name');
            }
        });

        // 8. channel_tickets, data_sources, data_activities, data_aux_reasons, data_status_tickets, data_group_agents, data_fulfillments, data_max_handles
        $tables = [
            'channel_tickets', 'data_sources', 'data_activities', 'data_aux_reasons', 
            'data_status_tickets', 'data_group_agents', 'data_fulfillments', 'data_max_handles'
        ];
        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) use ($t) {
                if (!Schema::hasColumn($t, 'status')) {
                    $table->string('status')->default('Aktif')->after('name');
                }
            });
        }

        // 9. data_holidays
        Schema::table('data_holidays', function (Blueprint $table) {
            if (!Schema::hasColumn('data_holidays', 'start_date')) {
                $table->date('start_date')->nullable()->after('name');
            }
            if (!Schema::hasColumn('data_holidays', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('data_holidays', 'status')) {
                $table->string('status')->default('Aktif')->after('end_date');
            }
        });

        // 10. data_sites
        Schema::table('data_sites', function (Blueprint $table) {
            if (!Schema::hasColumn('data_sites', 'location')) {
                $table->string('location')->nullable()->after('name');
            }
            if (!Schema::hasColumn('data_sites', 'status')) {
                $table->string('status')->default('Aktif')->after('location');
            }
        });

        // 11. department_escalation_units
        Schema::table('department_escalation_units', function (Blueprint $table) {
            if (!Schema::hasColumn('department_escalation_units', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('department_escalation_units', 'status')) {
                $table->string('status')->default('Aktif')->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Typically we don't drop columns in sync migrations as it's unsafe, 
        // but for completeness one could implement it.
    }
};
