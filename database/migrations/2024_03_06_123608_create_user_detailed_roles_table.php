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
     10954147 - addStudent
     10954147 - editStudent
     
     */
    public function up(): void
    {
        Schema::create('user_detailed_roles', function (Blueprint $table) {
            $table->id();
            $table->longText("UserId")->nullable();
            $table->longText("RoleFunction")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_detailed_roles');
    }
};
