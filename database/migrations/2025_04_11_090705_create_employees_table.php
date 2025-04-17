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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mda_id');
            $table->unsignedBigInteger('paygroup_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('step_id');
            $table->string('employee_number')->unique();
            $table->string('surname');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('religion')->nullable();
            $table->date('first_appointment_date')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->date('present_appointment_date')->nullable();
            $table->date('retirement_date')->nullable();
            $table->string('rank')->nullable();
            $table->string('lga')->nullable();
            $table->string('qualifications')->nullable();
            $table->decimal('net_pay', 12, 2)->nullable();
            $table->text('passport')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('mda_id')->references('id')->on('mdas')->onDelete('cascade');
            $table->foreign('paygroup_id')->references('id')->on('pay_groups')->onDelete('cascade');
            $table->foreign('level_id')->references('id')->on('grade_levels')->onDelete('cascade');
            $table->foreign('step_id')->references('id')->on('steps')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
