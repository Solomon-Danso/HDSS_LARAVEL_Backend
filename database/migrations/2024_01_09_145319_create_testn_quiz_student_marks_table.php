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
        Schema::create('testn_quiz_student_marks', function (Blueprint $table) {
            $table->id();
            $table->integer("QuestionId")->nullable();
            $table->longText("QuizId")->nullable();
            $table->longText("StudentAnswer")->nullable();
            $table->double("Mark")->nullable();
            $table->longText("StudentId")->nullable();
            $table->dateTime("SolutionDate")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testn_quiz_student_marks');
    }
};
