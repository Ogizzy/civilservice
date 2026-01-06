<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE employee_leaves
            MODIFY approval_stage ENUM('hod', 'mda_head', 'completed')
            NOT NULL DEFAULT 'hod'
        ");

        DB::statement("
            ALTER TABLE employee_leaves
            MODIFY status ENUM('pending', 'approved', 'rejected')
            NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE employee_leaves
            MODIFY approval_stage ENUM('hod', 'mda_head', 'approved', 'rejected')
            NOT NULL DEFAULT 'hod'
        ");
    }
};
