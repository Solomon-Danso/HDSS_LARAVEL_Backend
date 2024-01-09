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
        Schema::create('lesson_notes', function (Blueprint $table) {
            $table->id();
            $table->longText("NotesTicket")->nullable();
            $table->longText("TeacherId")->nullable();
            $table->longText("TeacherName")->nullable();
            $table->longText("Subject")->nullable();
            $table->longText("Stage")->nullable();
            $table->integer("classSize")->nullable();
            $table->date("DateWritten")->nullable();
            $table->date("WeekStartDate")->nullable();
            $table->date("WeekEndDate")->nullable();
            $table->longText("Period")->nullable();
            $table->longText("Lesson")->nullable();
            $table->longText("strand")->nullable();
            $table->longText("substrand")->nullable();
            $table->longText("indicator")->nullable();
            $table->longText("performanceIndicator")->nullable();
            $table->longText("contentStandard")->nullable();
            $table->longText("coreCompetence")->nullable();
            $table->longText("keywords")->nullable();
            $table->longText("TLMS")->nullable();
            $table->longText("references")->nullable();
            $table->longText("Day1")->nullable();
            $table->longText("Day1Phase1")->nullable();
            $table->longText("Day1Phase2")->nullable();
            $table->longText("Day1Phase3")->nullable();

            $table->longText("Day2")->nullable();
            $table->longText("Day2Phase1")->nullable();
            $table->longText("Day2Phase2")->nullable();
            $table->longText("Day2Phase3")->nullable();

            $table->longText("Day3")->nullable();
            $table->longText("Day3Phase1")->nullable();
            $table->longText("Day3Phase2")->nullable();
            $table->longText("Day3Phase3")->nullable();

            $table->longText("Day4")->nullable();
            $table->longText("Day4Phase1")->nullable();
            $table->longText("Day4Phase2")->nullable();
            $table->longText("Day4Phase3")->nullable();

            $table->longText("Day5")->nullable();
            $table->longText("Day5Phase1")->nullable();
            $table->longText("Day5Phase2")->nullable();
            $table->longText("Day5Phase3")->nullable();

            $table->longText("HeadTeacherComment")->nullable();
            $table->dateTime("HeadTeacherDateSigned")->nullable();
           
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_notes');
    }
};
