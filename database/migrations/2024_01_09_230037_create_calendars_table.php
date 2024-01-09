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
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->longText("Title")->nullable();
            $table->longText("ClassName")->nullable();
            $table->longText("CalendarPath")->nullable();
            $table->longText("TeacherId")->nullable();
            $table->longText("TeacherName")->nullable();
            $table->longText("AcademicYear")->nullable();
            $table->longText("AcademicTerm")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
