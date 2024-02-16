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
        Schema::table('authentics', function (Blueprint $table) {
            $table->boolean("IsBlocked")->default(false);
            $table ->dateTime("LastLogout") -> nullable();
            $table ->dateTime("TimeSpent") -> nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authentics', function (Blueprint $table) {
            //
        });
    }
};
