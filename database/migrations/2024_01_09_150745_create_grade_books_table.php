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
        Schema::create('grade_books', function (Blueprint $table) {
            $table->id();
            $table->longText("QuizId")->nullable();
            $table->longText("StudentId")->nullable();
            $table->longText("StudentName")->nullable();
            $table->longText("Level")->nullable();
            $table->longText("ProfilePic")->nullable();
            $table->longText("SubjectName")->nullable();
            $table->longText("Position")->nullable();
            $table->dateTime("DateUploaded")->nullable();
            $table->double("MarksObtained")->nullable();
            $table->double("TotalObtained")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_books');
    }
};
