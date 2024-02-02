<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CSVFile;
use Illuminate\Support\Facades\DB;
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

            // Salvo il percorso da file perché dovrò rileggerlo
            $request->session()->put('filePath', $filePath);

            // Apri il file in modalità lettura
            $handle = fopen($filePath, 'r');

            // Inizializa un array per contenere i dati del CSV
            $csvData = [];

            $counter = 0;

            // Leggi ogni riga del CSV
            while (($row = fgetcsv($handle)) !== false) {
                //$rowData = [];

                $csvData[] = $row;

                $counter++;

                // Interrompi while dopo 10 righe
                if ($counter >= 10) {
                    break;
                }
            }
            // dd($row);
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
        // scelte dell'utente
        $selectValues = $request->json('selectValues');
        #dd($selectValues);

        $filePath = $request->session()->get('filePath');
        #dd($filePath);

        $handle = fopen($filePath, 'r'); //modalità lettura
        $csvData = []; //inizializza array
        $counter = 0;
        // Leggi ogni riga del CSV
        while (($row = fgetcsv($handle)) !== false) {
            $csvData[] = $row;
            $counter++;

            // Interrompi while dopo 10 righe
            if ($counter >= 10000) {
                break;
            }
        }
        fclose($handle); //Chiudi file
        //dd($csvData);

        $dataToInsert = [];

        foreach ($csvData as $rowData) {
            $record = [];

            foreach ($selectValues as $index => $colonna) {
                if ($colonna !== '0' && isset($rowData[$index])) {
                    $record[$colonna] = $rowData[$index];
                }
            }

            $dataToInsert[] = $record;
        }

        $chunks = array_chunk($dataToInsert, 1000);

        // DB::insert('insert into csv_files' . $chunks);

        foreach ($chunks as $chunk) {
            DB::table('csv_files')->insert($chunk);
        }

        return response()->json(['message' => 'Dati salvati con successo'], 200);
    }
}

/* foreach ($chunks as $chunk) {
    $values = [];

    //Ottengo l'elenco delle colonne per il blocco corrente
    $columns = array_keys($chunk[0]);

    foreach ($chunk as $record) {
        $values[] = '(' . implode(', ', array_map(fn ($value) => "'" . addslashes($value) . "'", $record)) . ')';
    }

    $columnsString = implode(', ', $columns);
    $valuesString = implode(', ', $values);

    $query = "INSERT INTO csv_files ($columnsString) VALUES $valuesString";

    DB::statement($query);
} */