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
            $table->longtext("SessionID")->nullable();
            $table->longtext("Status")->nullable();
            $table->dateTime("expirationTime")->nullable();

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
