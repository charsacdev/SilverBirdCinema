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
        Schema::create('ticket_history_management', function (Blueprint $table) {
            $table->id();
            $table->string('partner_id');
            $table->string('user_id');
            $table->string('scanner_id');
            $table->string('generate_id');
            $table->string('ticket_id');
            $table->string('ticket_type');
            $table->string('ticket_price');
            $table->string('ticket_status');
            $table->string('ticket_qrcode');
            $table->string('from_date');
            $table->string('to_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_history_management');
    }
};
