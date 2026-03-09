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
        Schema::create('recordings', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->dateTime('call_date')->nullable();
            $table->string('ticket_number')->nullable();
            $table->string('disposition')->nullable();
            $table->string('customer')->nullable();
            $table->string('agent')->nullable();
            $table->string('duration')->nullable();
            $table->string('recording_file')->nullable();
            $table->string('stt')->nullable();
            $table->string('qa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recordings');
    }
};
