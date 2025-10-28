@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-plus"></i> Planifier une Nouvelle Maintenance</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('maintenance.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="equipment_id" class="form-label">Équipement *</label>
                                    <select class="form-control @error('equipment_id') is-invalid @enderror" 
                                            id="equipment_id" name="equipment_id" required>
                                        <option value="">Sélectionnez un équipement...</option>
                                        @foreach($equipment as $item)
                                            <option value="{{ $item->id }}" 
                                                {{ old('equipment_id', request('equipment_id')) == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }} ({{ $item->serial_number }}) - {{ $item->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('equipment_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type de maintenance *</label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Sélectionnez le type...</option>
                                        <option value="preventive" {{ old('type') == 'preventive' ? 'selected' : '' }}>Maintenance préventive</option>
                                        <option value="corrective" {{ old('type') == 'corrective' ? 'selected' : '' }}>Maintenance corrective</option>
                                        <option value="predictive" {{ old('type') == 'predictive' ? 'selected' : '' }}>Maintenance prédictive</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scheduled_date" class="form-label">Date et heure prévues *</label>
                                    <input type="datetime-local" class="form-control @error('scheduled_date') is-invalid @enderror" 
                                           id="scheduled_date" name="scheduled_date" 
                                           value="{{ old('scheduled_date') }}" required>
                                    @error('scheduled_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="technician_name" class="form-label">Technicien *</label>
                                    <input type="text" class="form-control @error('technician_name') is-invalid @enderror" 
                                           id="technician_name" name="technician_name" 
                                           value="{{ old('technician_name') }}" required
                                           placeholder="Nom du technicien responsable">
                                    @error('technician_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description de la maintenance *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Décrivez la maintenance à effectuer..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="problem_reported" class="form-label">Problème signalé (pour maintenance corrective)</label>
                            <textarea class="form-control @error('problem_reported') is-invalid @enderror" 
                                      id="problem_reported" name="problem_reported" rows="2"
                                      placeholder="Décrivez le problème rencontré...">{{ old('problem_reported') }}</textarea>
                            @error('problem_reported')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Informations</h6>
                            <ul class="mb-0">
                                <li><strong>Préventive :</strong> Maintenance planifiée régulièrement</li>
                                <li><strong>Corrective :</strong> Réparation suite à un problème</li>
                                <li><strong>Prédictive :</strong> Basée sur l'analyse des données</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Planifier la maintenance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Définir la date et heure minimale à aujourd'hui
    document.getElementById('scheduled_date').min = new Date().toISOString().slice(0, 16);
</script>
@endsection