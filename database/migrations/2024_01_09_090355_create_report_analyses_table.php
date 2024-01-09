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
        Schema::create('report_analyses', function (Blueprint $table) {
            $table->id();
            $table->longText("ThisTermPosition");
            $table->float("ThisTermTotalScoreObtained");
            $table->float("ThisTermEntireTotalScore");
            $table->float("ThisTermAverageScore");
            $table->integer("ThisTermTotalPass");
            $table->integer("ThisTermTotalFailed");
            $table->longText("ThisTermAcademicTerm");
            $table->longText("ThisTermAcademicYear");
            $table->longText("ClassName");
            $table->longText("StudentId");
            $table->longText("StudentName");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_analyses');
    }
};
