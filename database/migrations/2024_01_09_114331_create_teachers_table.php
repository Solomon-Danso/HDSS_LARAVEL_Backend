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
            $table->longText("Title")->nullable();
            $table->longText("FirstName")->nullable();
            $table->longText("OtherName")->nullable();
            $table->longText("LastName")->nullable();
            $table->date("DateOfBirth")->nullable();
            $table->longText("Gender")->nullable();
            $table->longText("MaritalStatus")->nullable();
            $table->longText("Location")->nullable();
            $table->longText("Country")->nullable();
            $table->longText("PhoneNumber")->nullable();
            $table->longText("Email")->nullable();
            $table->longText("Education")->nullable();
            $table->longText("TeachingExperience")->nullable();
            $table->longText("TaxNumber")->nullable();
            $table->longText("SSNITNumber")->nullable();
            $table->longText("HealthStatus")->nullable();
            $table->longText("EmergencyContacts")->nullable();
            $table->longText("EmergencyPhone")->nullable();
            $table->double("Salary")->nullable();
            $table->double("Debit")->nullable();
            $table->double("Credit")->nullable();
            $table->longText("StaffID")->nullable();
            $table->longText("Role")->nullable();
            $table->longText("SpecificRole")->nullable();
            $table->longText("FilePath")->nullable();
            $table->longText("CertPath")->nullable();
            $table->longText("IdCards")->nullable();
            $table->longText("Position")->nullable();
            $table->date("StartDate")->nullable();
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
