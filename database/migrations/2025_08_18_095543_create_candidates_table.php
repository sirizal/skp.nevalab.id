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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gender')->nullable(); // e.g
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('resume_file')->nullable(); // Path to the candidate's resume file
            $table->string('identification_number')->nullable(); // Unique identification number (e.g., SSN, national ID)
            $table->string('identification_file')->nullable(); // Path to the identification document file
            $table->string('place_of_birth')->nullable(); // City or country of birth
            $table->date('date_of_birth')->nullable();
            $table->string('status')->default('applied'); // e.g., applied, interviewed, hired, rejected
            $table->string('position_applied')->nullable(); // Position the candidate applied for
            $table->string('health_status')->nullable(); // Health status of the candidate
            $table->string('marital_status')->nullable(); // e.g., single, married, divorced
            $table->string('illness_history')->nullable(); // Any known illness history
            $table->string('ability_work_shift')->nullable(); // e.g., day shift, night shift, flexible
            $table->text('notes')->nullable(); // Additional notes about the candidate
            $table->string('education_level')->nullable(); // e.g., high school, bachelor's, master's
            $table->string('skills')->nullable(); // Comma-separated list of skills
            $table->date('application_date')->nullable(); // Date when the application was submitted
            $table->date('interview_date')->nullable(); // Date of the interview, if applicable
            $table->boolean('is_active')->default(true); // Indicates if the candidate is still active in the system
            $table->unsignedBigInteger('user_id')->nullable(); // ID of the user who created the candidate record
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');    
            $table->timestamps();
            $table->softDeletes(); // Enables soft deletes for the candidates table
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
