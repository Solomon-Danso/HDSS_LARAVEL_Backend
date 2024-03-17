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
        Schema::table('transport_acts', function (Blueprint $table) {
            $table->dateTime("ExactPickupDate")->nullable();
            $table->dateTime("ExactDepartureDate")->nullable();
            $table->dateTime("ExactDestinationDate")->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transport_acts', function (Blueprint $table) {
            //
        });
    }
};
