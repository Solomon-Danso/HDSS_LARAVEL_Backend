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
        Schema::create('transport_acts', function (Blueprint $table) {
            $table->id();
            $table->longText("CompanyId")->nullable();
            $table->longText("StudentId")->nullable();
            $table->longText("StudentPic")->nullable();
            $table->longText("StudentName")->nullable();
            $table->longText("StudentLevel")->nullable();
            $table->longText("ParentName")->nullable();
            $table->longText("ParentContact")->nullable();
            $table->longText("ParentAltContact")->nullable();
            $table->longText("ParentEmail")->nullable();
            $table->double("TransportFare")->nullable();
           
            $table->boolean("Pickup")->default(false);
            $table->dateTime("PickupTime")->nullable();
            $table->longText("PickupLatitude")->nullable();
            $table->longText("PickupLongtitude")->nullable();
            $table->longText("PickupLocationUrl")->nullable();
            $table->longText("PickupConductorId")->nullable();
            $table->longText("PickupConductorName")->nullable();
            $table->longText("PickupConductorPic")->nullable();


            $table->boolean("Departure")->default(false);
            $table->dateTime("DepartureTime")->nullable();
            $table->longText("DepartureLatitude")->nullable();
            $table->longText("DepartureLongtitude")->nullable();
            $table->longText("DepartureLocationUrl")->nullable();
            $table->longText("DepartureConductorId")->nullable();
            $table->longText("DepartureConductorName")->nullable();
            $table->longText("DepartureConductorPic")->nullable();

            $table->boolean("DestinationArrival")->default(false);
            $table->dateTime("DestinationArrivalTime")->nullable();
            $table->longText("DestinationArrivalLatitude")->nullable();
            $table->longText("DestinationArrivalLongtitude")->nullable();
            $table->longText("DestinationArrivalLocationUrl")->nullable();
            $table->longText("DestinationArrivalConductorId")->nullable();
            $table->longText("DestinationArrivalConductorName")->nullable();
            $table->longText("DestinationArrivalConductorPic")->nullable();





            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_acts');
    }
};
