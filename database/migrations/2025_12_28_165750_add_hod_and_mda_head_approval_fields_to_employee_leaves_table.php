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
             $table->text('hod_remarks')->nullable()->after('remarks');
            $table->text('mda_head_remarks')->nullable()->after('hod_remarks');

            $table->timestamp('hod_approved_at')->nullable()->after('hod_remarks');
            $table->timestamp('mda_head_approved_at')->nullable()->after('mda_head_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_leaves', function (Blueprint $table) {
            $table->dropColumn([
                'hod_remarks',
                'mda_head_remarks',
                'hod_approved_at',
                'mda_head_approved_at',
            ]);
            
        });
    }
};
