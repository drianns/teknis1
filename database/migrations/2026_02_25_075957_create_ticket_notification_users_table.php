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
        Schema::create('ticket_notification_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['Yes', 'No'])->default('Yes');
            $table->boolean('is_ticket_create')->default(false);
            $table->boolean('is_ticket_over_sla')->default(false);
            $table->boolean('is_ticket_closed')->default(false);
            $table->boolean('is_ticket_escalation')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_notification_users');
    }
};
