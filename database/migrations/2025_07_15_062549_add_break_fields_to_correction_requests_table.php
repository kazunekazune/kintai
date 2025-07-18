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
        Schema::table('attendance_correction_requests', function (Blueprint $table) {
            $table->time('requested_break_start')->nullable()->after('requested_clock_out');
            $table->time('requested_break_end')->nullable()->after('requested_break_start');
            $table->text('requested_note')->nullable()->after('requested_memo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_correction_requests', function (Blueprint $table) {
            $table->dropColumn(['requested_break_start', 'requested_break_end', 'requested_note']);
        });
    }
};
