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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->longText("CompanyId")->nullable();
            $table->longText("StudentId")->nullable();
            $table->longText("ProfilePic")->nullable();
            $table->longText("FirstName")->nullable();
            $table->longText("OtherName")->nullable();
            $table->longText("LastName")->nullable();
            $table->longText("Level")->nullable();
            $table->longText("ParentName")->nullable();
            $table->longText("ParentContact")->nullable();
            $table->longText("ParentAltContact")->nullable();

            $table->longText("Location")->nullable();
            $table->double("TransportFare")->nullable();
           


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
