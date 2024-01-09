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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->longText("StudentId")->nullable();
            $table->longText("FirstName")->nullable();
            $table->longText("OtherName")->nullable();
            $table->longText("LastName")->nullable();
            $table->date("DateOfBirth")->nullable();
            $table->longText("Gender")->nullable();
            $table->longText("HomeTown")->nullable();
            $table->longText("Location")->nullable();
            $table->longText("Country")->nullable();
            $table->longText("FathersName")->nullable();
            $table->longText("FatherOccupation")->nullable();
            $table->longText("MothersName")->nullable();
            $table->longText("MotherOccupation")->nullable();
            $table->longText("GuardianName")->nullable();
            $table->longText("GuardianOccupation")->nullable();
            $table->longText("ParentLocation")->nullable();
            $table->longText("ParentDigitalAddress")->nullable();
            $table->longText("ParentReligion")->nullable();
            $table->longText("ParentEmail")->nullable();
            $table->longText("EmergencyContactName")->nullable();
            $table->longText("EmergencyPhoneNumber")->nullable();
            $table->longText("EmergencyAlternatePhoneNumber")->nullable();
            $table->longText("RelationshipWithChild")->nullable();
            $table->longText("ParentPhoneNumber")->nullable();
            $table->longText("Balance")->nullable();
            $table->longText("Religion")->nullable();
            $table->longText("Email")->nullable();
            $table->longText("PhoneNumber")->nullable();
            $table->longText("AlternatePhoneNumber")->nullable();
            $table->longText("MedicalIInformation")->nullable();
            $table->longText("Level")->nullable();
            $table->longText("amountOwing")->nullable();
            $table->longText("creditAmount")->nullable();
            $table->date("AdmissionDate")->nullable();
            $table->double("SchoolBankAccount")->nullable();
            $table->longText("ProfilePic")->nullable();
            $table->longText("Role")->nullable();
            $table->longText("TheAcademicYear")->nullable();
            $table->longText("TheAcademicTerm")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
