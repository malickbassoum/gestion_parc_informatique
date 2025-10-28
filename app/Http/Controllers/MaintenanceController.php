<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Equipment;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('view_maintenance')) {
            abort(403, 'Accès non autorisé.');
        }

        $query = Maintenance::with('equipment')->latest();

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('technician')) {
            $query->where('technician_name', 'like', '%' . $request->technician . '%');
        }

        $maintenances = $query->paginate(10);

        return view('maintenance.index', compact('maintenances'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('create_maintenance')) {
            abort(403, 'Accès non autorisé.');
        }

        $equipment = Equipment::where('status', '!=', 'out_of_service')->get();
        
        return view('maintenance.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('create_maintenance')) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:preventive,corrective,predictive',
            'description' => 'required|string',
            'problem_reported' => 'nullable|string',
            'scheduled_date' => 'required|date',
            'technician_name' => 'required|string|max:255'
        ]);

        $validated['status'] = 'scheduled';

        Maintenance::create($validated);

        // Mettre à jour le statut de l'équipement
        Equipment::find($validated['equipment_id'])->update(['status' => 'maintenance']);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance planifiée avec succès.');
    }

    public function show(Maintenance $maintenance)
    {
        if (!auth()->user()->hasPermission('view_maintenance')) {
            abort(403, 'Accès non autorisé.');
        }

        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        if (!auth()->user()->hasPermission('edit_maintenance')) {
            abort(403, 'Accès non autorisé.');
        }

        $equipment = Equipment::all();
        
        return view('maintenance.edit', compact('maintenance', 'equipment'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        if (!auth()->user()->hasPermission('edit_maintenance')) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:preventive,corrective,predictive',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'description' => 'required|string',
            'problem_reported' => 'nullable|string',
            'work_performed' => 'nullable|string',
            'parts_used' => 'nullable|string',
            'cost' => 'nullable|numeric',
            'scheduled_date' => 'required|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'technician_name' => 'required|string|max:255'
        ]);

        // Gérer les pièces utilisées
        if ($request->filled('parts_used')) {
            $parts = array_map('trim', explode(',', $request->parts_used));
            $validated['parts_used'] = json_encode($parts);
        } else {
            $validated['parts_used'] = null;
        }

        $maintenance->update($validated);

        // Mettre à jour le statut de l'équipement si nécessaire
        if (in_array($validated['status'], ['completed', 'cancelled'])) {
            $maintenance->equipment->update(['status' => 'operational']);
        } elseif ($validated['status'] == 'in_progress') {
            $maintenance->equipment->update(['status' => 'maintenance']);
        }

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Maintenance mise à jour avec succès.');
    }

    public function start(Maintenance $maintenance)
    {
        if (!auth()->user()->hasPermission('start_maintenance')) {
            abort(403, 'Accès non autorisé.');
        }

        $maintenance->update([
            'status' => 'in_progress',
            'start_date' => now()
        ]);

        // Mettre à jour le statut de l'équipement
        $maintenance->equipment->update(['status' => 'maintenance']);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Maintenance démarrée avec succès.');
    }

    public function complete(Request $request, Maintenance $maintenance)
    {
        if (!auth()->user()->hasPermission('complete_maintenance')) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'work_performed' => 'required|string',
            'parts_used' => 'nullable|string',
            'cost' => 'nullable|numeric'
        ]);

        // Gérer les pièces utilisées
        $partsUsed = [];
        if ($request->filled('parts_used')) {
            $partsUsed = array_map('trim', explode(',', $request->parts_used));
        }

        $maintenance->update([
            'status' => 'completed',
            'end_date' => now(),
            'work_performed' => $validated['work_performed'],
            'parts_used' => json_encode($partsUsed),
            'cost' => $validated['cost'],
            'duration_minutes' => $maintenance->start_date ? $maintenance->start_date->diffInMinutes(now()) : null
        ]);

        // Mettre à jour le statut de l'équipement
        $maintenance->equipment->update(['status' => 'operational']);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Maintenance terminée avec succès.');
    }

    public function destroy(Maintenance $maintenance)
    {
        if (!auth()->user()->hasPermission('delete_maintenance')) {
            abort(403, 'Accès non autorisé.');
        }

        // Remettre l'équipement en état opérationnel si la maintenance était en cours
        if (in_array($maintenance->status, ['scheduled', 'in_progress'])) {
            $maintenance->equipment->update(['status' => 'operational']);
        }

        $maintenance->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance supprimée avec succès.');
    }
}