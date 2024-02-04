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
           $table->boolean("IsLoggedIn")->default(false);
           $table->boolean("IsPasswordReset")->default(false);
           $table->longText("RawPassword") -> nullable();
           $table ->dateTime("LastLogin") -> nullable();
           $table ->longText("DeviceType") -> nullable();
           $table ->longText("DeviceName") -> nullable();
           $table ->longText("OS") -> nullable();
           $table ->longText("Country") -> nullable();
           $table ->longText("City") -> nullable();
           $table ->longText("CompanyId") -> nullable();

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
