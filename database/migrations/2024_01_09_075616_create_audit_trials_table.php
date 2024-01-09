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
        Schema::create('audit_trials', function (Blueprint $table) {
            $table->id();
            $table->longText("IpAddress");
            $table->longText("BrowserType");
            $table->longText("DeviceType");
            $table->longText("UserLocation");
            $table->longText("Country");
            $table->longText("City");
            $table->longText("ActionDescription");
            $table->longText("Maker");
            $table->longText("Role");
            $table->longText("Level");
            $table->longText("Email");
            $table->longText("UserId");
            $table->longText("ProfilePic");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trials');
    }
};
