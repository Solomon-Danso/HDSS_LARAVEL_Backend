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
        Schema::create('testn_quiz_teachers', function (Blueprint $table) {
            $table->id();
            $table->longText("QuizId")->nullable();
            $table->longText("Subject")->nullable();
            $table->longText("Level")->nullable();
            $table->longText("Question")->nullable();
            $table->longText("OptionA")->nullable();
            $table->longText("OptionB")->nullable();
            $table->longText("OptionC")->nullable();
            $table->longText("OptionD")->nullable();
            $table->longText("OptionE")->nullable();
            $table->longText("Answer")->nullable();
            $table->boolean("IsAnswered")->default(false);
            $table->dateTime("Deadline")->nullable();
            $table->integer("Duration")->nullable();
            $table->longText("TeacherId")->nullable();
            $table->longText("TeacherName")->nullable();
            $table->longText("ProfilePic")->nullable();
            $table->float("DesignatedMarks")->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testn_quiz_teachers');
    }
};
