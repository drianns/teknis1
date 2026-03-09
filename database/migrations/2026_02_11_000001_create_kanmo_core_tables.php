<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("token")->nullable();
            $table->string("distribution_type")->default('first_pickup');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('id')->nullable();
            $table->unsignedBigInteger('role_id')->after('company_id')->nullable();
        });

        Schema::create('user_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id');
            $table->string('full_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('aux')->default('available');
            $table->timestamps();
        });

        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('chat_ticket_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::create('channel_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('chat_ticket_user_id')->nullable();
            $table->string('account_id');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_headers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('channel_user_id');
            $table->string('status')->default('open');
            $table->timestamps();
        });

        Schema::create('chat_header_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('chat_header_id')->nullable();
            $table->unsignedBigInteger('chat_ticket_user_id');
            $table->unsignedBigInteger('user_agent_id')->nullable();
            $table->string('ticket_number');
            $table->string('priority')->nullable();
            $table->string('status')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->text('question')->nullable();
            $table->text('answer')->nullable();
            $table->timestamps();
        });

        Schema::create('result_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('user_agent_id')->nullable();
            $table->tinyInteger('flaging')->nullable();
            $table->string('ticket_number')->nullable();
            $table->string('status')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('channel_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('channel_id');
            $table->string('page_id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('channel_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('channel_id');
            $table->string('account_id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('channel_accounts');
        Schema::dropIfExists('channel_pages');
        Schema::dropIfExists('result_tickets');
        Schema::dropIfExists('chat_header_tickets');
        Schema::dropIfExists('chat_headers');
        Schema::dropIfExists('channel_users');
        Schema::dropIfExists('chat_ticket_users');
        Schema::dropIfExists('channels');
        Schema::dropIfExists('user_agents');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company_id', 'role_id']);
        });
        Schema::dropIfExists('roles');
        Schema::dropIfExists('companies');
    }
};
