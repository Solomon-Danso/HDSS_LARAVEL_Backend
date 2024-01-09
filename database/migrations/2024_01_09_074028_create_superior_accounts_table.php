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
        Schema::create('superior_accounts', function (Blueprint $table) {
            $table->id();
            $table->longText("StaffID");
            $table->longText("Name");
            $table->longText("Contact");
            $table->longText("Email");
            $table->longText("Role");
            $table->longText("SpecificRole");
            $table->longText("ProfilePicturePath");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('superior_accounts');
    }
};
