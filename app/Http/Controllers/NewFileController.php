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


            ###
            // Inizializzo il contenuto HTML per la tabella
            $tableHtml = '<div class=""><table class="table table-bordered"><thead><tr>';

            # if (!empty($csvData)) {
            $headers = array_shift($csvData);

            // Aggiungo gli header alla tabella
            foreach ($headers as $header) {
                $tableHtml .= '<th>' . $header . '</th>';
            }
            # }

            $tableHtml .= '</tr></thead><tbody>';

            // Itera su ogni riga dei dati CSV
            foreach ($csvData as $rowData) {
                $tableHtml .= '<tr>';

                // Itera su ogni cella della riga
                foreach ($rowData as $cellData) {
                    $tableHtml .= '<td>' . $cellData . '</td>';
                }

                $tableHtml .= '</tr>';
            }

            $tableHtml .= '</tbody></table></div>';

            return view('tablePage', ['tableHtml' => $tableHtml])
                ->with('status', 'File caricato con successo!☑️');
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }
    }
}
