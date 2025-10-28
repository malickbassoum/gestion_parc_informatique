@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Gestion du Parc Informatique</h1>
                <a href="{{ route('equipment.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvel Équipement
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Liste des Équipements ({{ $equipment->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($equipment->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nom</th>
                                    <th>Numéro de série</th>
                                    <th>Catégorie</th>
                                    <th>Marque/Modèle</th>
                                    <th>Statut</th>
                                    <th>Localisation</th>
                                    <th>Maintenances</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipment as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->name }}</strong>
                                        @if($item->notes)
                                            <i class="fas fa-sticky-note text-muted" title="{{ $item->notes }}"></i>
                                        @endif
                                    </td>
                                    <td><code>{{ $item->serial_number }}</code></td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->category }}</span>
                                    </td>
                                    <td>{{ $item->brand }} {{ $item->model }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($item->status == 'operational') bg-success
                                            @elseif($item->status == 'maintenance') bg-warning
                                            @else bg-danger @endif">
                                            @if($item->status == 'operational')
                                                <i class="fas fa-check-circle"></i>
                                            @elseif($item->status == 'maintenance')
                                                <i class="fas fa-tools"></i>
                                            @else
                                                <i class="fas fa-times-circle"></i>
                                            @endif
                                            {{ $item->status }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $item->maintenance_status }}</small>
                                    </td>
                                    <td>{{ $item->location }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $item->maintenances_count }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('equipment.show', $item) }}" class="btn btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('equipment.edit', $item) }}" class="btn btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Supprimer" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Modal de suppression -->
                                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmer la suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir supprimer l'équipement <strong>"{{ $item->name }}"</strong> ?</p>
                                                        <p class="text-danger"><small>Cette action est irréversible.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('equipment.destroy', $item) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-desktop fa-3x text-muted mb-3"></i>
                        <h4>Aucun équipement enregistré</h4>
                        <p class="text-muted">Commencez par ajouter votre premier équipement.</p>
                        <a href="{{ route('equipment.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un équipement
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection