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
        Schema::create('billing_cards', function (Blueprint $table) {
            $table->id();
            $table->longText("StudentId");
            $table->double("OpeningBalance");
            $table->double("Transaction");
            $table->double("ClosingBalance");
            $table->double("Bills");
            $table->longText("AcademicYear");
            $table->longText("AcademicTerm");
            $table->longText("Level");
            $table->date("TransactionDate");
            $table->longText("TransactionId");
            $table->longText("Action");
            $table->longText("PaymentMethod");
            $table->longText("StaffId");
            $table->longText("StaffName");


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_cards');
    }
};
