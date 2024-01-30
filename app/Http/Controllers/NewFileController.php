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

            $columns = config('db_table_fields');

            // dd($columns);

            #test
            // $request->session()->put('csvData', $csvData);
            #test

            return view('tablePage', compact('csvData', 'columns', 'headers'))
                ->with('status', 'File caricato con successo!☑️');
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }
    }

    public function salvaDati(Request $request)
    {

        $selectValues = $request->json('selectValues');
        dd($selectValues);
        // Recupera i dati dalla sessione
        $csvData = $request->session()->get('csvData');
        #dd($csvData);
        // Crea un nuovo record utilizzando il modello CSVFile
        $nuovoRecord = new CSVFile;
        #dd($nuovoRecord);
        // Assegna i valori ai campi del record utilizzando i nomi delle colonne
        foreach ($selectValues as $index => $colonna) {
            // Verifica se la colonna è presente nell'array degli header selezionati dall'utente
            if (in_array($colonna, $selectValues)) {
                // Ottieni l'indice della colonna nell'array originale dei dati
                $colIndex = array_search($colonna, $selectValues);
                // Utilizza il valore corrispondente nella riga corrente del CSVData
                $nuovoRecord->{$colonna} = $csvData[0][$colIndex];
            }
        }
        dd($nuovoRecord);

        // Salva il record nel database
        $nuovoRecord->save();

        return response()->json(['message' => 'Dati salvati con successo'], 200);
    }
    /* public function salvaDati(Request $request)
    {
        $selectValues = $request->json('selectValues');
        $csvData = $request->session()->get('csvData');

        foreach ($csvData as $rowData) {
            $nuovoRecord = new CSVFile;

            foreach ($selectValues as $index => $colonna) {
                if (isset($rowData[$index])) { // verifica che l'indice esista nel CSV
                    $nuovoRecord->{$colonna} = $rowData[$index];
                }
            }

            $nuovoRecord->save();
        }

        return response()->json(['message' => 'Dati salvati con successo'], 200);
    }
 */
}
