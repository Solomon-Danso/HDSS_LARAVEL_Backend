<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /*
    NB: No user can have more than 1 summarised role, unless 
    1. The user roles is edited 
    2. The functionality or model is added to the user in the user_detailed_roles table

    The UserRole will be displayed in the frontend 
    example 
    Solomon Danso 
    superadmin
    */
    public function up(): void
    {
        Schema::create('user_summarised_roles', function (Blueprint $table) {
            $table->id();
            $table->longText("UserId")->nullable();
            $table->longText("RoleName")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_summarised_roles');
    }
};
