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
        Schema::create('time_tables', function (Blueprint $table) {
            $table->id();
            $table->longText("CompanyId")->nullable();
            $table->longText("ClassName")->nullable();
            $table->longText("SlidePath")->nullable();
            $table->longText("AcademicYear")->nullable();
            $table->longText("AcademicTerm")->nullable();
            $table->longText("StaffID")->nullable();
            $table->longText("TeacherName")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_tables');
    }
};
