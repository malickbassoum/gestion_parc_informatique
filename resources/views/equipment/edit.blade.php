@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Modifier l'Équipement</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('equipment.update', $equipment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de l'équipement *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $equipment->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="serial_number" class="form-label">Numéro de série *</label>
                                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                           id="serial_number" name="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}" required>
                                    @error('serial_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Catégorie *</label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="Ordinateur portable" {{ old('category', $equipment->category) == 'Ordinateur portable' ? 'selected' : '' }}>Ordinateur portable</option>
                                        <option value="Ordinateur fixe" {{ old('category', $equipment->category) == 'Ordinateur fixe' ? 'selected' : '' }}>Ordinateur fixe</option>
                                        <option value="Serveur" {{ old('category', $equipment->category) == 'Serveur' ? 'selected' : '' }}>Serveur</option>
                                        <option value="Imprimante" {{ old('category', $equipment->category) == 'Imprimante' ? 'selected' : '' }}>Imprimante</option>
                                        <option value="Réseau" {{ old('category', $equipment->category) == 'Réseau' ? 'selected' : '' }}>Équipement réseau</option>
                                        <option value="Périphérique" {{ old('category', $equipment->category) == 'Périphérique' ? 'selected' : '' }}>Périphérique</option>
                                        <option value="Autre" {{ old('category', $equipment->category) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="brand" class="form-label">Marque *</label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                           id="brand" name="brand" value="{{ old('brand', $equipment->brand) }}" required>
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="model" class="form-label">Modèle *</label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                           id="model" name="model" value="{{ old('model', $equipment->model) }}" required>
                                    @error('model')
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
                                        <option value="operational" {{ old('status', $equipment->status) == 'operational' ? 'selected' : '' }}>Opérationnel</option>
                                        <option value="maintenance" {{ old('status', $equipment->status) == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                                        <option value="out_of_service" {{ old('status', $equipment->status) == 'out_of_service' ? 'selected' : '' }}>Hors service</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="purchase_date" class="form-label">Date d'achat</label>
            <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" 
                   id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $equipment->purchase_date ? $equipment->purchase_date->format('Y-m-d') : '') }}">
            @error('purchase_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="purchase_price" class="form-label">Prix d'achat (€)</label>
            <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" 
                   id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $equipment->purchase_price) }}"
                   placeholder="0.00">
            @error('purchase_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Localisation *</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location', $equipment->location) }}" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="specifications" class="form-label">Spécifications techniques</label>
                            <textarea class="form-control @error('specifications') is-invalid @enderror" 
                                      id="specifications" name="specifications" rows="3">{{ old('specifications', $equipment->specifications) }}</textarea>
                            @error('specifications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2">{{ old('notes', $equipment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('equipment.show', $equipment) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <div class="btn-group">
                                <a href="{{ route('equipment.show', $equipment) }}" class="btn btn-info">
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