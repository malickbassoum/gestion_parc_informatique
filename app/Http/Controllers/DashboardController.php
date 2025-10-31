<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard', [
        'totalEquipment' => Equipment::count(),
        'underMaintenance' => Equipment::where('status', 'maintenance')->count(),
        'pendingMaintenance' => Maintenance::where('status', 'scheduled')->count(),
        'totalUsers' => User::count(),
        //'equipmentNeedingMaintenance' => Equipment::where('needs_maintenance', true)->get(),
        'recentMaintenance' => Maintenance::with('equipment')
            ->whereNotNull('end_date') // ← Ajouter cette ligne
            ->where('status', 'completed')
            ->orderBy('end_date', 'desc')
            ->limit(5)
            ->get()
    ]);
    }   
}