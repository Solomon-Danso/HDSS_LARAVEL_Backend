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
        Schema::create('video_calls', function (Blueprint $table) {
            $table->id();
            $table->longText("TeacherId")->nullable();
            $table->longText("TeacherName")->nullable();
            $table->longText("Level")->nullable();
            $table->longText("Subject")->nullable();
            $table->longText("AcademicYear")->nullable();
            $table->longText("AcademicTerm")->nullable();
            $table->longText("VideoCallUrl")->nullable();
            $table->dateTime("StartDate")->nullable();
            $table->longText("CompanyId")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_calls');
    }
};
