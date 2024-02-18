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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->longText("CompanyId")->nullable();
            $table->longText("Title")->nullable();
            $table->longText("FirstName ")->nullable();
            $table->longText("OtherName")->nullable();
            $table->longText("LastName")->nullable();
            $table->dateTime("DateOfBirth")->nullable();
            $table->longText("Gender")->nullable();
            $table->longText("MaritalStatus")->nullable();
            $table->longText("HomeTown")->nullable();
            $table->longText("Location")->nullable();
            $table->longText("Country")->nullable();
            $table->longText("PhoneNumber")->nullable();
            $table->longText("Email")->nullable();
            $table->longText("HighestEducationalLevel")->nullable();
            $table->longText("TeachingExperience")->nullable();
            $table->longText("TaxNumber")->nullable();
            $table->longText("SocialSecurity")->nullable();
            $table->longText("HealthStatus")->nullable();
            $table->longText("EmergencyPerson")->nullable();
            $table->longText("EmergencyPhone1")->nullable();
            $table->longText("EmergencyPhone2")->nullable();
            $table->longText("ProfilePic")->nullable();
            $table->longText("TeacherId")->nullable();
            $table->longText("Cert1")->nullable();
            $table->longText("Cert2")->nullable();
            $table->longText("IdCards")->nullable();
            $table->boolean("IsSuspended")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
