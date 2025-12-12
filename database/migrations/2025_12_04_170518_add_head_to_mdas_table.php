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
        Schema::table('mdas', function (Blueprint $table) {

             Schema::table('mdas', function (Blueprint $table) {
            if (!Schema::hasColumn('mdas', 'head_id')) {
                $table->foreignId('head_id')->nullable()->after('mda_code')->constrained('employees')->nullOnDelete();
            }
        });

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mdas', function (Blueprint $table) {
            //
        });
    }
};
