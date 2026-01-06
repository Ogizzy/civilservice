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
        Schema::table('employee_leaves', function (Blueprint $table) {
            $table->enum('approval_stage', ['hod', 'mda_head', 'approved', 'rejected'])
                ->default('hod')
                ->after('status');

            $table->foreignId('hod_approved_by')->nullable()->after('approval_stage');
            $table->foreignId('mda_approved_by')->nullable()->after('hod_approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_leaves', function (Blueprint $table) {
            //
        });
    }
};
