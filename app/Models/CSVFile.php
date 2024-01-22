<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSVFile extends Model
{
    use HasFactory;

    // altrimenti di default prende c_s_v_files
    protected $table = 'csv_files';

    protected $fillable = [
        'orderDate',
        'invoiceNumber',
        'customerName',
        'productID',
        'productName',
        'category',
        'quantityBought',
        'sellingPrice',
        'unitCost',
        'InvoiceSales',
        'InvoiceCost',
        'shipmentDate',
    ];
}
