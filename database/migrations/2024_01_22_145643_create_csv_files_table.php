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
        Schema::create('csv_files', function (Blueprint $table) {
            $table->id();

            $table->timestamp('orderDate')->nullable();
            $table->integer('invoiceNumber');

            $table->integer('customerID')->nullable(); #file150k :(

            $table->string('customerName');
            $table->integer('productID');
            $table->string('productName');
            $table->string('category');
            $table->integer('quantityBought');
            $table->decimal('sellingPrice', 8, 2);
            $table->decimal('unitCost', 8, 2);
            $table->decimal('InvoiceSales', 8, 2);
            $table->decimal('InvoiceCost', 8, 2);
            $table->timestamp('shipmentDate')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csv_files');
    }
};
