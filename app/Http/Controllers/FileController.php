<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{

    public function upload(Request $request)
    {
        // Validazione del file
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,pdf,csv|max:10000',
        ]);

        // Ottenere il file dalla richiesta
        $file = $request->file('file');

        // Generare un nome univoco per il file (puoi personalizzare questa parte)
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Salva il file nella directory di archiviazione (default: storage/app/public/uploads)
        $file->storeAs('uploads', $fileName, 'public');

        // Altri processi o salvataggi nel database, se necessario

        return redirect()->back()->with('success', 'File caricato con successo!');
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
