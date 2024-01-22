<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSVFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'orderDate',
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
