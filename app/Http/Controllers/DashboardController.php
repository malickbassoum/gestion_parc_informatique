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
        try {
            // Statistiques de base
            $totalEquipment = Equipment::count();
            $underMaintenance = Equipment::where('status', 'maintenance')->count();
            $pendingMaintenance = Maintenance::where('status', 'scheduled')->count();
            $totalUsers = User::count();

            // Maintenances récentes
            $recentMaintenance = Maintenance::with('equipment')
                ->where('status', 'completed')
                ->latest()
                ->take(5)
                ->get();

            // Équipements nécessitant une maintenance
            $equipmentNeedingMaintenance = Equipment::where('status', 'operational')
                ->whereHas('maintenances', function($query) {
                    $query->where('status', 'completed')
                          ->where('end_date', '<', now()->subDays(90));
                })
                ->orWhereDoesntHave('maintenances')
                ->take(5)
                ->get();

            return view('dashboard', compact(
                'totalEquipment',
                'underMaintenance',
                'pendingMaintenance',
                'totalUsers',
                'recentMaintenance',
                'equipmentNeedingMaintenance'
            ));

        } catch (\Exception $e) {
            // En cas d'erreur, passer des valeurs par défaut
            return view('dashboard', [
                'totalEquipment' => 0,
                'underMaintenance' => 0,
                'pendingMaintenance' => 0,
                'totalUsers' => 0,
                'recentMaintenance' => collect(),
                'equipmentNeedingMaintenance' => collect(),
            ]);
        }
    }
}