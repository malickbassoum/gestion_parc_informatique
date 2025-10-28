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

            <!-- Filtres et Recherche -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Filtres et Recherche</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('equipment.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Nom, n° série, marque...">
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="all">Tous les statuts</option>
                                    <option value="operational" {{ request('status') == 'operational' ? 'selected' : '' }}>Opérationnel</option>
                                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                                    <option value="out_of_service" {{ request('status') == 'out_of_service' ? 'selected' : '' }}>Hors service</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="category" class="form-label">Catégorie</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="all">Toutes catégories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="sort" class="form-label">Trier par</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom</option>
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date création</option>
                                    <option value="purchase_date" {{ request('sort') == 'purchase_date' ? 'selected' : '' }}>Date achat</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="order" class="form-label">Ordre</label>
                                <select class="form-select" id="order" name="order">
                                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="{{ route('equipment.index') }}" class="btn btn-secondary">
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
                                    <h4 class="mb-0">{{ $totalEquipment }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-desktop fa-2x"></i>
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
                                    <h6 class="card-title mb-0">Opérationnel</h6>
                                    <h4 class="mb-0">{{ $statusCounts['operational'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
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
                                    <h6 class="card-title mb-0">En maintenance</h6>
                                    <h4 class="mb-0">{{ $statusCounts['maintenance'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tools fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Hors service</h6>
                                    <h4 class="mb-0">{{ $statusCounts['out_of_service'] }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des Équipements</h5>
                    <div class="text-muted">
                        Affichage de {{ $equipment->firstItem() }} à {{ $equipment->lastItem() }} sur {{ $equipment->total() }} équipement(s)
                    </div>
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

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Page {{ $equipment->currentPage() }} sur {{ $equipment->lastPage() }}
                        </div>
                        <nav aria-label="Pagination">
                            <ul class="pagination mb-0">
                                {{ $equipment->links() }}
                            </ul>
                        </nav>
                    </div>

                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-desktop fa-3x text-muted mb-3"></i>
                        <h4>Aucun équipement trouvé</h4>
                        <p class="text-muted">Aucun équipement ne correspond à vos critères de recherche.</p>
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