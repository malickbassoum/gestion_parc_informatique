@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('maintenance.index') }}">Maintenances</a></li>
                    <li class="breadcrumb-item active">Détails</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-tools text-primary"></i>
                    Détails de la Maintenance
                </h1>
                <div class="btn-group">
                    <a href="{{ route('maintenance.edit', $maintenance) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
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
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Informations de la Maintenance</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Équipement:</strong>
                                    <p class="mb-2">{{ $maintenance->equipment->name }}</p>
                                    
                                    <strong>Numéro de série:</strong>
                                    <p class="mb-2">{{ $maintenance->equipment->serial_number }}</p>
                                    
                                    <strong>Type de maintenance:</strong>
                                    <p>
                                        <span class="badge 
                                            @if($maintenance->type == 'preventive') bg-primary
                                            @elseif($maintenance->type == 'corrective') bg-danger
                                            @else bg-info @endif">
                                            {{ $maintenance->type }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Statut:</strong>
                                    <p>
                                        <span class="badge 
                                            @if($maintenance->status == 'completed') bg-success
                                            @elseif($maintenance->status == 'in_progress') bg-warning
                                            @elseif($maintenance->status == 'scheduled') bg-info
                                            @else bg-secondary @endif">
                                            {{ $maintenance->status }}
                                        </span>
                                    </p>
                                    
                                    <strong>Technicien:</strong>
                                    <p class="mb-2">{{ $maintenance->technician_name }}</p>
                                    
                                    <strong>Coût:</strong>
                                    <p class="mb-2">
                                        @if($maintenance->cost)
                                            <strong>{{ number_format($maintenance->cost, 2) }} €</strong>
                                        @else
                                            <span class="text-muted">Non spécifié</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <strong>Description:</strong>
                            <p class="mb-0">
                                @if($maintenance->description)
                                    {{ $maintenance->description }}
                                @else
                                    <span class="text-muted">Aucune description</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Détails de planification -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Planification</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Date prévue:</strong>
                                    <p>{{ $maintenance->scheduled_date->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Date de début:</strong>
                                    <p>
                                        @if($maintenance->start_date)
                                            {{ $maintenance->start_date->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">Non débutée</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Date de fin:</strong>
                                    <p>
                                        @if($maintenance->end_date)
                                            {{ $maintenance->end_date->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">Non terminée</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <strong>Durée:</strong>
                            <p>
                                @if($maintenance->duration_minutes)
                                    {{ $maintenance->duration_minutes }} minutes
                                @elseif($maintenance->start_date && !$maintenance->end_date)
                                    <span class="text-warning">En cours</span>
                                @else
                                    <span class="text-muted">Non spécifiée</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Section pour terminer la maintenance -->
                    @if($maintenance->status == 'in_progress')
                    <div id="complete" class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Terminer la Maintenance</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('maintenance.complete', $maintenance) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cost" class="form-label">Coût (€)</label>
                                            <input type="number" step="0.01" class="form-control" id="cost" 
                                                   name="cost" value="{{ old('cost', $maintenance->cost) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration_minutes" class="form-label">Durée (minutes)</label>
                                            <input type="number" class="form-control" id="duration_minutes" 
                                                   name="duration_minutes" value="{{ old('duration_minutes', $maintenance->duration_minutes) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes de fin</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-flag-checkered"></i> Terminer la Maintenance
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions rapides -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($maintenance->status == 'scheduled')
                                    <form action="{{ route('maintenance.start', $maintenance) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 mb-2">
                                            <i class="fas fa-play"></i> Démarrer la Maintenance
                                        </button>
                                    </form>
                                @endif

                                @if($maintenance->status == 'in_progress')
                                    <a href="#complete" class="btn btn-primary w-100 mb-2">
                                        <i class="fas fa-flag-checkered"></i> Terminer la Maintenance
                                    </a>
                                @endif

                                @if(in_array($maintenance->status, ['scheduled', 'in_progress']))
                                    <form action="{{ route('maintenance.cancel', $maintenance) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger w-100 mb-2" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette maintenance ?')">
                                            <i class="fas fa-times"></i> Annuler la Maintenance
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('maintenance.destroy', $maintenance) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette maintenance ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Informations techniques -->
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">Informations Techniques</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Créée le:</strong><br>
                            {{ $maintenance->created_at->format('d/m/Y H:i') }}</p>
                            
                            <p><strong>Modifiée le:</strong><br>
                            {{ $maintenance->updated_at->format('d/m/Y H:i') }}</p>
                            
                            @if($maintenance->completed_by)
                                <p><strong>Terminée par:</strong><br>
                                {{ $maintenance->completedBy->name ?? 'Utilisateur inconnu' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection