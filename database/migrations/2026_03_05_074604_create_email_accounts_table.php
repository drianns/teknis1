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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('incoming_user')->nullable();
            $table->string('incoming_pass')->nullable();
            $table->string('incoming_server')->nullable();
            $table->string('incoming_port')->nullable();
            $table->string('encrypted_connection')->nullable();
            $table->string('outgoing_user')->nullable();
            $table->string('outgoing_pass')->nullable();
            $table->string('outgoing_server')->nullable();
            $table->string('outgoing_port')->nullable();
            $table->string('encrypted_connection_out')->nullable();
            $table->boolean('need_login')->default(true);
            $table->boolean('incoming_back_up')->default(false);
            $table->boolean('outgoing_back_up')->default(false);
            $table->string('server_protocol')->nullable();
            $table->string('server_protocol_out')->nullable();
            $table->unsignedBigInteger('server_profile_id')->nullable();
            $table->unsignedBigInteger('email_signature_id')->nullable();
            $table->unsignedBigInteger('email_service_method_id')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
