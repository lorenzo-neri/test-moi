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
        /* Schema::create('csv_files', function (Blueprint $table) {
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
        }); */
        Schema::create('csv_files', function (Blueprint $table) {
            $table->id();

            $table->string('orderDate')->nullable();
            $table->string('invoiceNumber')->nullable();

            $table->string('customerID')->nullable(); #file150k :(

            $table->string('customerName')->nullable();
            $table->string('productID')->nullable();
            $table->string('productName')->nullable();
            $table->string('category')->nullable();
            $table->string('quantityBought')->nullable();
            $table->string('sellingPrice')->nullable();
            $table->string('unitCost')->nullable();
            $table->string('InvoiceSales')->nullable();
            $table->string('InvoiceCost')->nullable();
            $table->string('shipmentDate')->nullable();

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
