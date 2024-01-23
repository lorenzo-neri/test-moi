<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CSVFile;
use Carbon\Carbon;
use DateTime;

class FileController extends Controller
{

    public function upload(Request $request)
    {

        // MAPPATURA della TABELLA
        $columnMapping = [
            'orderDate' => 0,
            'invoiceNumber' => 1,
            'customerID' => 2,
            'customerName' => 3,
            'productID' => 4,
            'productName' => 5,
            'category' => 6,
            'quantityBought' => 7,
            'sellingPrice' => 8,
            'unitCost' => 9,
            'InvoiceSales' => 10,
            'InvoiceCost' => 11,
            'shipmentDate' => 12,
        ];


        try {
            // Validazione del file
            $request->validate([
                'file' => 'required|file|mimes:txt,csv,xlsx',
            ]);

            // Ottieni il file dalla richiesta
            $file = $request->file('file');

            // Genera un nome univoco per il file
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Salva il file nella directory di archiviazione
            $file->storeAs('uploads', $fileName, 'public');

            // ELABORAZIONE del CSV
            // Leggi il file CSV
            $filePath = storage_path('app/public/uploads/' . $fileName);

            // Apri il file in modalità lettura
            $handle = fopen($filePath, 'r');

            // Inizializa un array per contenere i dati del CSV
            $csvData = [];

            $counter = 0;
            // Leggi ogni riga del CSV
            while (($row = fgetcsv($handle)) !== false) {
                $rowData = [];

                //mapp dati in base alle colonne corrispondenti
                foreach ($columnMapping as $columnName => $columnIndex) {
                    $rowData[$columnName] = isset($row[$columnIndex]) ? $row[$columnIndex] : null;
                }

                $csvData[] = $rowData;

                $counter++;

                // Interrompi while dopo 20 righe
                if ($counter >= 20) {
                    break;
                }
            }

            // Chiudi file
            fclose($handle);

            // elaboro e inserisco i dati nel database
            foreach ($csvData as $row) {
                #dd($row);
                $format = 'Y-m-d';

                //verifico se la data nel CSV è valida
                if (DateTime::createFromFormat($format, $row['orderDate']) !== false) {

                    //aggiungo la gestione dinamica di customerID
                    $customerID = isset($row['customerID']) ? $row['customerID'] : null;


                    CSVFile::create([
                        'orderDate' => Carbon::createFromFormat($format, $row['orderDate'])->toDateTimeString(),
                        'invoiceNumber' => $row['invoiceNumber'],

                        'customerID' => $customerID,

                        'customerName' => $row['customerName'],
                        'productID' => $row['productID'],
                        'productName' => $row['productName'],
                        'category' => $row['category'],
                        'quantityBought' => $row['quantityBought'],
                        'sellingPrice' => str_replace(',', '.', $row['sellingPrice']), #
                        'unitCost' => $row['unitCost'],
                        'InvoiceSales' => str_replace(',', '.', $row['InvoiceSales']), #
                        'InvoiceCost' => str_replace(',', '.', $row['InvoiceCost']), #
                        'shipmentDate' => $row['shipmentDate'],
                    ]);
                }
            }

            return redirect()->back()->with('status', 'File caricato con successo!☑️');
        } catch (\Exception $e) {
            // Visualizza i dettagli dell'eccezione per il debugging
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }
    }





    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
