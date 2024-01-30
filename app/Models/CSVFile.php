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
        'data_ordine',
        'numero_fattura',
        'id_cliente',
        'nome_cliente',
        'producid_prodottotID',
        'nome_prodotto',
        'categoria',
        'quantità_acquistata',
        'prezzo_di_vendita',
        'costo_unità',
        'fattura_vendite',
        'fattura_costo',
        'data_di_spedizione',
    ];
}
