@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-tachometer-alt text-primary"></i>
                    Tableau de Bord - Gestion du Parc Informatique
                </h1>
                <div class="text-muted">
                    Bienvenue, <strong>{{ auth()->user()->name }}</strong>
                    @foreach(auth()->user()->roles as $role)
                        <span class="badge bg-secondary ms-1">{{ $role->name }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Cartes de statistiques -->
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Équipements Total</h5>
                                    <h2 class="card-text">{{ $totalEquipment ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-desktop fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">En Maintenance</h5>
                                    <h2 class="card-text">{{ $underMaintenance ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tools fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Maintenances Planifiées</h5>
                                    <h2 class="card-text">{{ $pendingMaintenance ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Utilisateurs</h5>
                                    <h2 class="card-text">{{ $totalUsers ?? 0 }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Actions Rapides -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-bolt"></i> Actions Rapides</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if(auth()->user()->hasPermission('create_equipment'))
                                <a href="{{ route('equipment.create') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus"></i> Ajouter un Équipement
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('create_maintenance'))
                                <a href="{{ route('maintenance.create') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-tools"></i> Planifier une Maintenance
                                </a>
                                @endif
                                
                                <a href="{{ route('equipment.index') }}" class="btn btn-info btn-lg">
                                    <i class="fas fa-list"></i> Voir tous les Équipements
                                </a>
                                
                                @if(auth()->user()->hasPermission('view_users'))
                                <a href="{{ route('users.index') }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-users"></i> Gérer les Utilisateurs
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Équipements nécessitant une maintenance -->
                    @if(isset($equipmentNeedingMaintenance) && $equipmentNeedingMaintenance->count() > 0)
                    <div class="card mt-4 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Maintenance Requise</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach($equipmentNeedingMaintenance as $equipment)
                                <a href="{{ route('equipment.show', $equipment) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $equipment->name }}</h6>
                                        <small class="text-danger">Maintenance requise</small>
                                    </div>
                                    <p class="mb-1 text-muted">{{ $equipment->serial_number }}</p>
                                    <small>Localisation: {{ $equipment->location }}</small>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Maintenances Récentes -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-history"></i> Maintenances Récentes</h5>
                        </div>
                        <div class="card-body">
                            @if(isset($recentMaintenance) && $recentMaintenance->count() > 0)
                                <div class="list-group">
                                    @foreach($recentMaintenance as $maintenance)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $maintenance->equipment->name ?? 'Équipement inconnu' }}</h6>
                                            <small class="text-muted">{{ $maintenance->end_date->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ Str::limit($maintenance->work_performed ?? 'Aucun détail', 50) }}</p>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Technicien: {{ $maintenance->technician_name }}</small>
                                            <small class="text-muted">
                                                @if($maintenance->cost)
                                                    {{ number_format($maintenance->cost, 2) }} €
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Aucune maintenance récente</h5>
                                    <p class="text-muted">Les maintenances terminées apparaîtront ici.</p>
                                    @if(auth()->user()->hasPermission('create_maintenance'))
                                    <a href="{{ route('maintenance.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Planifier une maintenance
                                    </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Statistiques supplémentaires -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Aperçu du Parc</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $operationalCount = $totalEquipment - $underMaintenance;
                                $operationalPercent = $totalEquipment > 0 ? round(($operationalCount / $totalEquipment) * 100) : 0;
                                $maintenancePercent = $totalEquipment > 0 ? round(($underMaintenance / $totalEquipment) * 100) : 0;
                            @endphp
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Équipements opérationnels</span>
                                    <span>{{ $operationalPercent }}%</span>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: {{ $operationalPercent }}%"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Équipements en maintenance</span>
                                    <span>{{ $maintenancePercent }}%</span>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $maintenancePercent }}%"></div>
                                </div>
                            </div>

                            <div class="row text-center mt-3">
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <h4 class="text-success mb-0">{{ $operationalCount }}</h4>
                                        <small class="text-muted">Opérationnel</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <h4 class="text-warning mb-0">{{ $underMaintenance }}</h4>
                                        <small class="text-muted">En maintenance</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection