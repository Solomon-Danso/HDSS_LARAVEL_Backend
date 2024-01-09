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
        Schema::create('amount_paids', function (Blueprint $table) {
            $table->id();
            $table->longText("StudentId");
            $table->longText("StudentName");
            $table->double("AmountDebtOld");
            $table->double("Amountpaid");
            $table->double("CreditAmount");
            $table->double("AmountDebtNew");
            $table->date("PaymentDate");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amount_paids');
    }
};
