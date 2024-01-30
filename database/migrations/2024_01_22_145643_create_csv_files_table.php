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

            $table->string('data_ordine')->nullable();
            $table->string('numero_fattura')->nullable();

            $table->string('id_cliente')->nullable(); #file150k :(

            $table->string('nome_cliente')->nullable();
            $table->string('id_prodotto')->nullable();
            $table->string('nome_prodotto')->nullable();
            $table->string('categoria')->nullable();
            $table->string('quantità_acquistata')->nullable();
            $table->string('prezzo_di_vendita')->nullable();
            $table->string('costo_unità')->nullable();
            $table->string('fattura_vendite')->nullable();
            $table->string('fattura_costo')->nullable();
            $table->string('data_di_spedizione')->nullable();

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
