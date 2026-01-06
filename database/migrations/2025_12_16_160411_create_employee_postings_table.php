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
        Schema::create('employee_postings', function (Blueprint $table) {
            $table->id();

             // Employee being posted
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();

            // Destination MDA
            $table->foreignId('mda_id')->constrained('mdas')->cascadeOnDelete();

            // Destination Department
            $table->foreignId('department_id')->nullable()->constrained('departments')
                  ->nullOnDelete();

            // Destination Unit
            $table->foreignId('unit_id')->nullable()->constrained('units')
                  ->nullOnDelete();

            // Posting period
            $table->date('posted_at');          // start date
            $table->date('ended_at')->nullable(); // end date (optional)

            // Posting classification
            $table->string('posting_type'); // Temporary | Permanent | Transfer | Deployment

            // Administrative notes
            $table->text('remarks')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_postings');
    }
};
