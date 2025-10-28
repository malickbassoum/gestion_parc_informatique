@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Modifier la Maintenance #{{ $maintenance->id }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('maintenance.update', $maintenance) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="equipment_id" class="form-label">Équipement *</label>
                                    <select class="form-control @error('equipment_id') is-invalid @enderror" 
                                            id="equipment_id" name="equipment_id" required>
                                        <option value="">Sélectionnez un équipement...</option>
                                        @foreach($equipment as $item)
                                            <option value="{{ $item->id }}" 
                                                {{ old('equipment_id', $maintenance->equipment_id) == $item->id ? 'selected' : '' }}>
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
                                        <option value="preventive" {{ old('type', $maintenance->type) == 'preventive' ? 'selected' : '' }}>Maintenance préventive</option>
                                        <option value="corrective" {{ old('type', $maintenance->type) == 'corrective' ? 'selected' : '' }}>Maintenance corrective</option>
                                        <option value="predictive" {{ old('type', $maintenance->type) == 'predictive' ? 'selected' : '' }}>Maintenance prédictive</option>
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
                                    <label for="status" class="form-label">Statut *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="scheduled" {{ old('status', $maintenance->status) == 'scheduled' ? 'selected' : '' }}>Planifiée</option>
                                        <option value="in_progress" {{ old('status', $maintenance->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                        <option value="completed" {{ old('status', $maintenance->status) == 'completed' ? 'selected' : '' }}>Terminée</option>
                                        <option value="cancelled" {{ old('status', $maintenance->status) == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="technician_name" class="form-label">Technicien *</label>
                                    <input type="text" class="form-control @error('technician_name') is-invalid @enderror" 
                                           id="technician_name" name="technician_name" 
                                           value="{{ old('technician_name', $maintenance->technician_name) }}" required>
                                    @error('technician_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scheduled_date" class="form-label">Date prévue *</label>
                                    <input type="datetime-local" class="form-control @error('scheduled_date') is-invalid @enderror" 
                                           id="scheduled_date" name="scheduled_date" 
                                           value="{{ old('scheduled_date', $maintenance->scheduled_date->format('Y-m-d\TH:i')) }}" required>
                                    @error('scheduled_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cost" class="form-label">Coût (€)</label>
                                    <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" 
                                           id="cost" name="cost" value="{{ old('cost', $maintenance->cost) }}">
                                    @error('cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description', $maintenance->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="problem_reported" class="form-label">Problème signalé</label>
                            <textarea class="form-control @error('problem_reported') is-invalid @enderror" 
                                      id="problem_reported" name="problem_reported" rows="2">{{ old('problem_reported', $maintenance->problem_reported) }}</textarea>
                            @error('problem_reported')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="work_performed" class="form-label">Travaux effectués</label>
                            <textarea class="form-control @error('work_performed') is-invalid @enderror" 
                                      id="work_performed" name="work_performed" rows="3">{{ old('work_performed', $maintenance->work_performed) }}</textarea>
                            @error('work_performed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="parts_used" class="form-label">Pièces utilisées</label>
                            <textarea class="form-control @error('parts_used') is-invalid @enderror" 
                                      id="parts_used" name="parts_used" rows="2"
                                      placeholder="Séparez les pièces par des virgules">{{ old('parts_used', $maintenance->parts_used ? implode(', ', json_decode($maintenance->parts_used, true)) : '') }}</textarea>
                            @error('parts_used')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('maintenance.show', $maintenance) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <div class="btn-group">
                                <a href="{{ route('maintenance.show', $maintenance) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection