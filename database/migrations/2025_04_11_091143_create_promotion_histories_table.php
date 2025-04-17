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
        Schema::create('promotion_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('previous_level');
            $table->unsignedBigInteger('previous_step');
            $table->unsignedBigInteger('current_level');
            $table->unsignedBigInteger('current_step');
            $table->enum('promotion_type', ['level advancement', 'step advancement']);
            $table->date('effective_date');
            $table->unsignedBigInteger('supporting_document');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('previous_level')->references('id')->on('grade_levels')->onDelete('cascade');
            $table->foreign('previous_step')->references('id')->on('steps')->onDelete('cascade');
            $table->foreign('current_level')->references('id')->on('grade_levels')->onDelete('cascade');
            $table->foreign('current_step')->references('id')->on('steps')->onDelete('cascade');
            $table->foreign('supporting_document')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_histories');
    }
};
