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
        Schema::create('school_fees_transactions', function (Blueprint $table) {
            $table->id();
            $table->longText("StudentId");
            $table->longText("StudentName");
            $table->double("OldAmountOwing");
            $table->double("CreditAmount");
            $table->double("THEAmountPaid");
            $table->double("NewAmountOwing");
            $table->date("PaymentDate");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_fees_transactions');
    }
};
