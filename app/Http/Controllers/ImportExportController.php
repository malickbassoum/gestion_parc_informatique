<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImportExportController extends Controller
{
    public function __construct()
    {
        // Temporairement enlever l'auth pour tester
        // $this->middleware('auth');
    }

    public function index()
    {
        Log::info('Accès page import-export');
        return view('import-export.index');
    }

    public function import(Request $request)
    {
        Log::info('Début importation', ['file' => $request->file('file')?->getClientOriginalName()]);

        // Validation basique
        if (!$request->hasFile('file')) {
            Log::warning('Aucun fichier reçu');
            return back()->with('error', 'Aucun fichier sélectionné.');
        }

        $file = $request->file('file');
        Log::info('Fichier reçu', [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType()
        ]);

        // Simulation réussie
        $successCount = rand(1, 5);
        
        Log::info('Importation simulée réussie', ['count' => $successCount]);

        return redirect('/import-export')
            ->with('success', "Importation simulée: $successCount équipement(s) importé(s) avec succès.");
    }

    public function export(Request $request)
    {
        Log::info('Exportation demandée', $request->all());

        $request->validate([
            'format' => 'required|in:xlsx,csv,pdf',
            'include_all' => 'sometimes|boolean'
        ]);

        $format = $request->format;
        $includeAll = $request->boolean('include_all', false);
        
        $equipmentCount = $includeAll ? Equipment::count() : Equipment::where('status', 'operational')->count();
        
        Log::info('Exportation simulée', ['format' => $format, 'count' => $equipmentCount]);

        return redirect('/import-export')
            ->with('success', "Exportation simulée: $equipmentCount équipement(s) au format $format.");
    }

    public function downloadTemplate()
    {
        Log::info('Téléchargement template');

        $filename = 'modele_import_equipements.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $data = [
            ['name', 'serial_number', 'category', 'brand', 'model', 'status', 'location'],
            ['Ordinateur Portable Dell', 'DL123456789', 'Ordinateur portable', 'Dell', 'Latitude 5420', 'operational', 'Bureau A1'],
            ['Imprimante HP', 'HP987654321', 'Imprimante', 'HP', 'LaserJet Pro', 'operational', 'Bureau B2']
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}