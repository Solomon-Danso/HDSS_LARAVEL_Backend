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
        Schema::create('term_results', function (Blueprint $table) {
            $table->id();
            $table->longText("StudentId");
            $table->longText("StudentName");
            $table->float("ClassScore");
            $table->float("ExamScore");
            $table->float("TotalScore");
            $table->longText("Position");
            $table->longText("Grade");
            $table->longText("Comment");
            $table->float("Average");
            $table->longText("Level");
            $table->longText("Subject");
            $table->longText("AcademicYear");
            $table->longText("AcademicTerm");
            $table->longText("TeacherId");
            $table->longText("TeacherName");
            $table->dateTime("SpecificDateAndTime");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('term_results');
    }
};
