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
        //Validazione del file
        /* $request->validate([
            'file' => 'required|file|mimes:txt,csv,xlsx',
        ]);


        //ottengo il file dalla richiesta
        $file = $request->file('file');

        //generaro un nome univoco per il file
        $fileName = time() . '_' . $file->getClientOriginalName();

        //salvo il file nella directory di archiviazione (default: storage/app/public/uploads)
        $file->storeAs('uploads', $fileName, 'public');


        // Elaborazione del CSV


        return redirect()->back()->with('success', 'File caricato con successo!'); */


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

            // Inizializza un array per contenere i dati del CSV
            $csvData = [];

            $counter = 0;
            // Leggi ogni riga del CSV
            while (($row = fgetcsv($handle)) !== false) {
                $csvData[] = $row;

                $counter++;

                // Interrompi il loop dopo 20 righe
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
                if (DateTime::createFromFormat($format, $row[0]) !== false) {

                    CSVFile::create([
                        'orderDate' => Carbon::createFromFormat($format, $row[0])->toDateTimeString(),
                        'invoiceNumber' => $row[1],
                        'customerName' => $row[2],
                        'productID' => $row[3],
                        'productName' => $row[4],
                        'category' => $row[5],
                        'quantityBought' => $row[6],
                        'sellingPrice' => str_replace(',', '.', $row[7]), #
                        'unitCost' => $row[8], #
                        'InvoiceSales' => str_replace(',', '.', $row[9]), #
                        'InvoiceCost' => str_replace(',', '.', $row[10]), #
                        'shipmentDate' => $row[11],
                    ]);
                }
            }

            return redirect()->back()->with('success', 'File caricato con successo!');
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
