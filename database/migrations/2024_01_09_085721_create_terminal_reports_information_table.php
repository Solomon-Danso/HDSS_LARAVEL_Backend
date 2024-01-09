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
        Schema::create('terminal_reports_information', function (Blueprint $table) {
            $table->id();
            $table->integer("Attendance");
            $table->integer("OutOf");
            $table->longText("PromotedTo");
            $table->longText("Conduct");
            $table->longText("Attitude");
            $table->longText("Interest");
            $table->longText("ClassTeacherRemarks");
            $table->longText("TeacherId");
            $table->longText("TeacherName");
            $table->longText("StudentId");
            $table->longText("StudentName");
            $table->longText("Level");
            $table->longText("AcademicYear");
            $table->longText("AcademicTerm");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminal_reports_information');
    }
};
