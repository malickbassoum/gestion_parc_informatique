@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Équipements</a></li>
                    <li class="breadcrumb-item active">{{ $equipment->name }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-desktop text-primary"></i>
                    {{ $equipment->name }}
                </h1>
                <div class="btn-group">
                    <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('maintenance.create') }}?equipment_id={{ $equipment->id }}" class="btn btn-success">
                        <i class="fas fa-tools"></i> Nouvelle Maintenance
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Informations principales -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Générales</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Numéro de série:</th>
                                    <td><code>{{ $equipment->serial_number }}</code></td>
                                </tr>
                                <tr>
                                    <th>Catégorie:</th>
                                    <td><span class="badge bg-secondary">{{ $equipment->category }}</span></td>
                                </tr>
                                <tr>
                                    <th>Marque/Modèle:</th>
                                    <td>{{ $equipment->brand }} {{ $equipment->model }}</td>
                                </tr>
                                <tr>
                                    <th>Statut:</th>
                                    <td>
                                        <span class="badge 
                                            @if($equipment->status == 'operational') bg-success
                                            @elseif($equipment->status == 'maintenance') bg-warning
                                            @else bg-danger @endif">
                                            @if($equipment->status == 'operational')
                                                <i class="fas fa-check-circle"></i>
                                            @elseif($equipment->status == 'maintenance')
                                                <i class="fas fa-tools"></i>
                                            @else
                                                <i class="fas fa-times-circle"></i>
                                            @endif
                                            {{ $equipment->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>État de maintenance:</th>
                                    <td><small class="text-muted">{{ $equipment->maintenance_status }}</small></td>
                                </tr>
                                <tr>
                                    <th>Localisation:</th>
                                    <td>{{ $equipment->location }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Informations d'achat -->
                    <!-- Informations d'achat -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Informations d'Achat</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Date d'achat:</th>
                                    <td>
                                        @if($equipment->purchase_date)
                                            {{ $equipment->purchase_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Non spécifiée</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Prix d'achat:</th>
                                    <td>
                                        @if($equipment->purchase_price)
                                            {{ number_format($equipment->purchase_price, 2) }} €
                                        @else
                                            <span class="text-muted">Non spécifié</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Âge:</th>
                                    <td>{{ $equipment->age }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                <!-- Spécifications et Notes -->
                <div class="col-md-6">
                    @if($equipment->specifications)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-cogs"></i> Spécifications Techniques</h5>
                        </div>
                        <div class="card-body">
                            <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $equipment->specifications }}</pre>
                        </div>
                    </div>
                    @endif

                    @if($equipment->notes)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notes</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $equipment->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Statistiques -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistiques</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Total des maintenances:</th>
                                    <td><span class="badge bg-info">{{ $equipment->maintenances->count() }}</span></td>
                                </tr>
                                <tr>
                                    <th>Maintenances préventives:</th>
                                    <td>
                                        {{ $equipment->maintenances->where('type', 'preventive')->count() }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Maintenances correctives:</th>
                                    <td>
                                        {{ $equipment->maintenances->where('type', 'corrective')->count() }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dernière maintenance:</th>
                                    <td>
                                        @php
                                            $lastMaintenance = $equipment->maintenances->where('status', 'completed')->sortByDesc('end_date')->first();
                                        @endphp
                                        @if($lastMaintenance)
                                            {{ $lastMaintenance->end_date->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">
                                                ({{ $lastMaintenance->end_date->diffForHumans() }})
                                            </small>
                                        @else
                                            <span class="text-muted">Aucune</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des maintenances -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Historique des Maintenances</h5>
                </div>
                <div class="card-body">
                    @if($maintenances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Technicien</th>
                                    <th>Description</th>
                                    <th>Coût</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenances as $maintenance)
                                <tr>
                                    <td>
                                        <strong>{{ $maintenance->scheduled_date->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $maintenance->scheduled_date->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($maintenance->type == 'preventive') bg-primary
                                            @elseif($maintenance->type == 'corrective') bg-danger
                                            @else bg-info @endif">
                                            {{ $maintenance->type }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($maintenance->status == 'completed') bg-success
                                            @elseif($maintenance->status == 'in_progress') bg-warning
                                            @elseif($maintenance->status == 'scheduled') bg-info
                                            @else bg-secondary @endif">
                                            {{ $maintenance->status }}
                                        </span>
                                    </td>
                                    <td>{{ $maintenance->technician_name }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $maintenance->description }}">
                                            {{ $maintenance->description }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($maintenance->cost)
                                            {{ number_format($maintenance->cost, 2) }} €
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('maintenance.show', $maintenance) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-tools fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Aucune maintenance enregistrée pour cet équipement.</p>
                        <a href="{{ route('maintenance.create') }}?equipment_id={{ $equipment->id }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Planifier une maintenance
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection