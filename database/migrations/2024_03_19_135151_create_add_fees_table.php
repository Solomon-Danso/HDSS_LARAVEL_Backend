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
        Schema::create('add_fees', function (Blueprint $table) {
            $table->id();
            $table->longText("CompanyId")->nullable();
            $table->longText("Level")->nullable();
            $table->longText("Description")->nullable();
            $table->longText("AcademicTerm")->nullable();
            $table->longText("AcademicYear")->nullable();
            $table->double("Fee")->nullable();
            $table->longText("SenderId")->nullable();
            $table->longText("SenderName")->nullable();
            $table->longText("ProfilePic")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_fees');
    }
};
