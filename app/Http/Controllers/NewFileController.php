<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CSVFile;
use Carbon\Carbon;
use DateTime;

class NewFileController extends Controller
{
    public function upload(Request $request)
    {
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

                $csvData[] = $row;

                $counter++;

                // Interrompi while dopo 10 righe
                if ($counter >= 10) {
                    break;
                }
            }

            # dd($handle, $csvData, $row);

            // Chiudi file
            fclose($handle);

            $headers = $csvData[0];


            return view('tablePage', ['csvData' => $csvData, 'headers' => $headers])
                ->with('status', 'File caricato con successo!☑️');
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }
    }
}
