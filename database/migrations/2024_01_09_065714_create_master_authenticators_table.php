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
        Schema::create('master_authenticators', function (Blueprint $table) {
            $table->id();
            $table->longText("Name");
            $table->longText("Role");
            $table->longText("SpecificUserRole");
            $table->longText("UserId");
            $table->longText("UserPassword");
            $table->longText("RawPassword");
            $table->longText("TwoSteps");
            $table->longText("TwoStepsExpire");
            $table->longText("PasswordToken");
            $table->dateTime("PasswordTokenExpire");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_authenticators');
    }
};
