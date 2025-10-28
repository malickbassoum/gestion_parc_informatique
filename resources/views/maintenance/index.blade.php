@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Maintenances</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-tools text-primary"></i>
                    Gestion des Maintenances
                </h1>
                <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle Maintenance
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('maintenance.index') }}">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label for="search" class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Description, technicien...">
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="all">Tous les statuts</option>
                                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Planifiée</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="all">Tous les types</option>
                                    <option value="preventive" {{ request('type') == 'preventive' ? 'selected' : '' }}>Préventive</option>
                                    <option value="corrective" {{ request('type') == 'corrective' ? 'selected' : '' }}>Corrective</option>
                                    <option value="predictive" {{ request('type') == 'predictive' ? 'selected' : '' }}>Prédictive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="technician" class="form-label">Technicien</label>
                                <input type="text" class="form-control" id="technician" name="technician" 
                                       value="{{ request('technician') }}" placeholder="Nom du technicien">
                            </div>
                            <div class="col-md-2">
                                <label for="sort" class="form-label">Trier par</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="scheduled_date" {{ request('sort') == 'scheduled_date' ? 'selected' : '' }}>Date prévue</option>
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date création</option>
                                    <option value="technician_name" {{ request('sort') == 'technician_name' ? 'selected' : '' }}>Technicien</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filtrer
                                    </button>
                                    <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Total</h6>
                                    <h4 class="mb-0">{{ $totalMaintenance }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tools fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Planifiées</h6>
                                    <h4 class="mb-0">{{ $statusCounts['scheduled'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">En cours</h6>
                                    <h4 class="mb-0">{{ $statusCounts['in_progress'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-play-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Terminées</h6>
                                    <h4 class="mb-0">{{ $statusCounts['completed'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Historique des Maintenances</h5>
                    <div class="text-muted">
                        Affichage de {{ $maintenances->firstItem() }} à {{ $maintenances->lastItem() }} sur {{ $maintenances->total() }} maintenance(s)
                    </div>
                </div>
                <div class="card-body">
                    @if($maintenances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Équipement</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Technicien</th>
                                    <th>Date prévue</th>
                                    <th>Durée</th>
                                    <th>Coût</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenances as $maintenance)
                                <tr>
                                    <td>
                                        <strong>{{ $maintenance->equipment->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $maintenance->equipment->serial_number }}</small>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($maintenance->type == 'preventive') bg-primary
                                            @elseif($maintenance->type == 'corrective') bg-danger
                                            @else bg-info @endif">
                                            <i class="fas 
                                                @if($maintenance->type == 'preventive') fa-shield-alt
                                                @elseif($maintenance->type == 'corrective') fa-wrench
                                                @else fa-chart-line @endif"></i>
                                            {{ $maintenance->type }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($maintenance->status == 'completed') bg-success
                                            @elseif($maintenance->status == 'in_progress') bg-warning
                                            @elseif($maintenance->status == 'scheduled') bg-info
                                            @else bg-secondary @endif">
                                            @if($maintenance->status == 'completed')
                                                <i class="fas fa-check-circle"></i>
                                            @elseif($maintenance->status == 'in_progress')
                                                <i class="fas fa-play-circle"></i>
                                            @elseif($maintenance->status == 'scheduled')
                                                <i class="fas fa-clock"></i>
                                            @else
                                                <i class="fas fa-times-circle"></i>
                                            @endif
                                            {{ $maintenance->status }}
                                        </span>
                                    </td>
                                    <td>{{ $maintenance->technician_name }}</td>
                                    <td>
                                        {{ $maintenance->scheduled_date->format('d/m/Y H:i') }}
                                        @if($maintenance->scheduled_date->isPast() && $maintenance->status == 'scheduled')
                                            <br>
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> En retard
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($maintenance->duration_minutes)
                                            {{ $maintenance->duration_minutes }} min
                                        @elseif($maintenance->start_date && !$maintenance->end_date)
                                            <span class="text-warning">En cours</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($maintenance->cost)
                                            <strong>{{ number_format($maintenance->cost, 2) }} €</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('maintenance.show', $maintenance) }}" class="btn btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('maintenance.edit', $maintenance) }}" class="btn btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            @if($maintenance->status == 'scheduled')
                                                <form action="{{ route('maintenance.start', $maintenance) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" title="Démarrer">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($maintenance->status == 'in_progress')
                                                <a href="{{ route('maintenance.show', $maintenance) }}#complete" 
                                                   class="btn btn-primary" title="Terminer">
                                                    <i class="fas fa-flag-checkered"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Page {{ $maintenances->currentPage() }} sur {{ $maintenances->lastPage() }}
                        </div>
                        <nav aria-label="Pagination">
                            <ul class="pagination mb-0">
                                {{ $maintenances->links() }}
                            </ul>
                        </nav>
                    </div>

                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                        <h4>Aucune maintenance trouvée</h4>
                        <p class="text-muted">Aucune maintenance ne correspond à vos critères de recherche.</p>
                        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Planifier une maintenance
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pagination .page-link {
    border-radius: 5px;
    margin: 0 2px;
}
.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
@endsection