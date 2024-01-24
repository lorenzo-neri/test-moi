<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CSVFile;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;

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

            // Conserva il nome del file nella session
            session(['uploadedFileName' => $fileName]);

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

    public function userChoice(Request $request)
    {
        try {
            // Recupera il nome del file dalla session
            $fileName = session('uploadedFileName');

            if (!$fileName) {
                throw new \Exception('Nome del file non trovato nella sessione.');
            }

            // Recupera i dati selezionati come array
            $selectedColumns = $request->input('selectedColumns', []);

            // Rimuovi il token CSRF dai dati selezionati
            $selectedColumns = array_filter($selectedColumns, function ($column) {
                return $column !== '_token';
            });

            // Ottieni la prima riga dei dati CSV (gli headers)
            $headers = array_values($selectedColumns);

            // Leggi il file CSV
            $filePath = storage_path('app/public/uploads/' . $fileName);
            // Apri il file in modalità lettura
            $handle = fopen($filePath, 'r');

            // Ottieni gli header (prima riga)
            $headers = fgetcsv($handle);

            // Verifica se il numero di elementi in $headers è valido
            if (!$headers || count($headers) === 0) {
                fclose($handle);
                return redirect()->back()->with('error', 'File CSV non valido.');
            }

            // Leggi il resto del file riga per riga
            while (($row = fgetcsv($handle)) !== false) {
                // Verifica se il numero di elementi in $headers è uguale al numero di elementi in $row
                if (count($headers) === count($row)) {
                    // Crea un nuovo record nel database utilizzando i dati selezionati
                    $dataToSave = array_combine($headers, $row);

                    // Converti il prezzo di vendita nel formato corretto (sostituisci la virgola con il punto)
                    if (isset($dataToSave['sellingPrice'])) {
                        $dataToSave['sellingPrice'] = str_replace(',', '.', $dataToSave['sellingPrice']);
                    }

                    // Converti l'importo della vendita della fattura nel formato corretto (sostituisci la virgola con il punto)
                    if (isset($dataToSave['InvoiceSales'])) {
                        $dataToSave['InvoiceSales'] = str_replace(',', '.', $dataToSave['InvoiceSales']);
                    }

                    // Aggiungi i dati all'array di inserimento
                    $dataToInsert[] = $dataToSave;
                } else {
                    //  Puoi gestire questo caso in modo diverso, ad esempio ignorando la riga o segnalando un errore
                    // echo "Le dimensioni di headers e row non corrispondono.";
                }
            }

            // Chiudi il file
            fclose($handle);

            // Prepara la query SQL di inserimento
            $insertQuery = "INSERT INTO csv_files (" . implode(", ", $headers) . ") VALUES ";

            // Aggiungi i valori per ciascuna riga
            foreach ($dataToInsert as $data) {
                $insertQuery .= "(" . implode(", ", array_map(function ($value) {
                    return "'" . addslashes($value) . "'";
                }, $data)) . "), ";
            }

            // Rimuovi l'ultima virgola
            $insertQuery = rtrim($insertQuery, ", ");

            // Esegui la query di inserimento
            DB::table('csv_files')->insert($dataToInsert);

            return redirect()->back()->with('status', 'Dati salvati nel database con successo! ☑️');
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }
    }
}
