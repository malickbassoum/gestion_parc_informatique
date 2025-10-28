<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImportExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!auth()->user()->hasPermission('create_equipment') && !auth()->user()->hasPermission('view_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        return view('import-export.index');
    }

    public function import(Request $request)
    {
        if (!auth()->user()->hasPermission('create_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            // Pour l'instant, on va simuler l'importation
            // Dans une vraie application, vous utiliseriez Maatwebsite/Excel
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            
            // Simulation d'importation réussie
            $successCount = rand(1, 10);
            
            return redirect()->route('import-export.index')
                ->with('success', "$successCount équipement(s) importé(s) avec succès (simulation).");

        } catch (\Exception $e) {
            return redirect()->route('import-export.index')
                ->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        if (!auth()->user()->hasPermission('view_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'format' => 'required|in:xlsx,csv,pdf',
            'include_all' => 'sometimes|boolean'
        ]);

        try {
            $format = $request->format;
            $includeAll = $request->boolean('include_all', false);
            
            // Simulation d'exportation
            $equipmentCount = $includeAll ? Equipment::count() : Equipment::where('status', 'operational')->count();
            
            return redirect()->route('import-export.index')
                ->with('success', "Exportation simulée: $equipmentCount équipement(s) au format $format.");

        } catch (\Exception $e) {
            return redirect()->route('import-export.index')
                ->with('error', 'Erreur lors de l\'exportation: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        if (!auth()->user()->hasPermission('create_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        try {
            // Créer un simple fichier CSV comme template
            $filename = 'modele_import_equipements.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'name', 'serial_number', 'category', 'brand', 'model', 
                'status', 'specifications', 'purchase_date', 'purchase_price', 
                'location', 'notes'
            ]);
            
            // Exemple de données
            fputcsv($handle, [
                'Ordinateur Portable Dell',
                'DL123456789',
                'Ordinateur portable',
                'Dell',
                'Latitude 5420',
                'operational',
                'Intel i5, 8GB RAM, 256GB SSD',
                '2024-01-15',
                '1200.00',
                'Bureau A1',
                'Équipement exemple'
            ]);

            fclose($handle);

            return response()->streamDownload(function() use ($handle) {
                //
            }, $filename, $headers);

        } catch (\Exception $e) {
            return redirect()->route('import-export.index')
                ->with('error', 'Erreur lors du téléchargement du modèle: ' . $e->getMessage());
        }
    }

    public function previewImport(Request $request)
    {
        if (!auth()->user()->hasPermission('create_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            // Simulation de prévisualisation
            $previewData = [
                [
                    'name' => 'Ordinateur Portable Dell',
                    'serial_number' => 'DL123456789',
                    'category' => 'Ordinateur portable',
                    'status' => 'operational'
                ],
                [
                    'name' => 'Imprimante HP',
                    'serial_number' => 'HP987654321',
                    'category' => 'Imprimante',
                    'status' => 'operational'
                ]
            ];

            return response()->json([
                'success' => true,
                'preview' => $previewData,
                'total_rows' => 2
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la prévisualisation: ' . $e->getMessage()
            ]);
        }
    }
}