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
        Schema::create('sales', function (Blueprint $table) {
            $table->id('idSales');
            $table->integer('idUser');
            $table->integer('idTransaction');
            $table->string('detailTransactionSale');
            $table->integer('idCabang');
            $table->integer('subtotal');
            $table->enum('payment', ['cash', 'qris']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
