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
        Schema::table('employee_postings', function (Blueprint $table) {
            $table->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_postings', function (Blueprint $table) {
        $table->dropForeign(['posted_by']);
        $table->dropColumn('posted_by');
        });
    }
};
