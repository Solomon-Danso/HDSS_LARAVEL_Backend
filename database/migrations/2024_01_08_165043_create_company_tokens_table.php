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
        Schema::create('company_tokens', function (Blueprint $table) {
            $table->id();
            $table->longText("CompanyId")->nullable();
            $table->longText("CompanyLogo")->nullable();
            $table->longText("CompanyName")->nullable();
            $table->longText("Location")->nullable();
            $table->longText("ContactPerson")->nullable();
            $table->longText("CompanyPhone")->nullable();
            $table->longText("CompanyEmail")->nullable();
            $table->longText("ContactPersonPhone")->nullable();
            $table->longText("ContactPersonEmail")->nullable();
            $table->longText("CompanyStatus")->nullable();
            $table->longText("Token")->nullable();
            $table->integer("Subcriptions")->nullable();
            $table->dateTime("StartDate")->nullable();
            $table->dateTime("SystemDate")->nullable();
            $table->dateTime("CurrentDate")->nullable();
            $table->dateTime("ExpireDate")->nullable();
            $table->longText("TokenStatus")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_tokens');
    }
};
