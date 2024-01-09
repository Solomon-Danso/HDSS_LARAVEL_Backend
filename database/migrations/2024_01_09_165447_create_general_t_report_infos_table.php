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
        Schema::create('general_t_report_infos', function (Blueprint $table) {
            $table->id();
            $table->longText("AcademicTerm")->nullabble();
            $table->longText("AcademicYear")->nullabble();
            $table->dateTime("VacationDate")->nullabble();
            $table->dateTime("ReOpeningDate")->nullabble();
            $table->longText("CompanyId")->nullabble();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_t_report_infos');
    }
};
