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
     Example: 
     Admin - addStudent
     Admin - editStudent
     
     */
    public function up(): void
    {
        Schema::create('primary_roles', function (Blueprint $table) {
            $table->id();
            $table->longText("RoleName")->nullable();
            $table->longText("RoleFunction") ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('primary_roles');
    }
};
