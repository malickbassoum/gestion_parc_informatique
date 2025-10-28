<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!auth()->user()->hasPermission('view_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        $equipment = Equipment::withCount('maintenances')->get();
        
        return view('equipment.index', compact('equipment'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('create_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        return view('equipment.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('create_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:equipment',
            'category' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'specifications' => 'nullable|string',
            'purchase_date' => 'nullable|date', // Modifié en nullable
            'purchase_price' => 'nullable|numeric', // Modifié en nullable
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        Equipment::create($validated);

        return redirect()->route('equipment.index')
            ->with('success', 'Équipement ajouté avec succès.');
    }

    public function show(Equipment $equipment)
    {
        if (!auth()->user()->hasPermission('view_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        $maintenances = $equipment->maintenances()->latest()->get();
        
        return view('equipment.show', compact('equipment', 'maintenances'));
    }

    public function edit(Equipment $equipment)
    {
        if (!auth()->user()->hasPermission('edit_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        if (!auth()->user()->hasPermission('edit_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:equipment,serial_number,' . $equipment->id,
            'category' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'status' => 'required|string|in:operational,maintenance,out_of_service',
            'specifications' => 'nullable|string',
            'purchase_date' => 'nullable|date', // Modifié en nullable
            'purchase_price' => 'nullable|numeric', // Modifié en nullable
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $equipment->update($validated);

        return redirect()->route('equipment.index')
            ->with('success', 'Équipement modifié avec succès.');
    }

    public function destroy(Equipment $equipment)
    {
        if (!auth()->user()->hasPermission('delete_equipment')) {
            abort(403, 'Accès non autorisé.');
        }

        $equipment->delete();

        return redirect()->route('equipment.index')
            ->with('success', 'Équipement supprimé avec succès.');
    }
}