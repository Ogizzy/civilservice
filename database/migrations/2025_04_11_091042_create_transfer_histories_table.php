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
        Schema::create('transfer_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('previous_mda');
            $table->unsignedBigInteger('current_mda');
            $table->date('effective_date');
            $table->unsignedBigInteger('supporting_document');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('previous_mda')->references('id')->on('mdas')->onDelete('cascade');
            $table->foreign('current_mda')->references('id')->on('mdas')->onDelete('cascade');
            $table->foreign('supporting_document')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_histories');
    }
};
