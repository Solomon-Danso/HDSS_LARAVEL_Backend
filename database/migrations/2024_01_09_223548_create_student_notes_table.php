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
        Schema::create('student_notes', function (Blueprint $table) {
            $table->id();
            $table->longText("CompanyId")->nullable();
            $table->longText("StudentId")->nullable();
            $table->longText("FullName")->nullable();
            $table->longText("Level")->nullable();
            $table->longText("ResourceUrl")->nullable();
            $table->longText("ResourceType")->nullable();
            $table->longText("Notes")->nullable();
            $table->longText("Subject")->nullable();
            $table->longText("AcademicTerm")->nullable();
            $table->longText("AcademicYear")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_notes');
    }
};
