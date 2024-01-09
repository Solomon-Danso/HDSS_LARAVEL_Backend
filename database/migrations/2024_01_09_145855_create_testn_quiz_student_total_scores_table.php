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
        Schema::create('testn_quiz_student_total_scores', function (Blueprint $table) {
            $table->id();
            $table->double("MarksObtained");
            $table->double("TotalScore");
            $table->longText("StudentId");
            $table->longText("StudentName");
            $table->longText("ProfilePic");
            $table->longText("QuizId");
            $table->longText("SubjectName");
            $table->longText("Level");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testn_quiz_student_total_scores');
    }
};
