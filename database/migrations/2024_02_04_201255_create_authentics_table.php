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
        Schema::create('authentics', function (Blueprint $table) {
            $table->id();
            $table->longText("UserId") -> nullable();
            $table->longText("ProfilePic") -> nullable();
            $table->longText("Role") -> nullable();
            $table->longText("UserName") -> nullable();
            $table -> longText("Password") -> nullable();
            $table -> longText("PasswordResetToken") -> nullable();
            $table -> dateTime("PasswordResetTokenExpire") -> nullable();
            $table -> integer("LoginAttempt") -> nullable();
            $table->longText("IpAddress") -> nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentics');
    }
};
