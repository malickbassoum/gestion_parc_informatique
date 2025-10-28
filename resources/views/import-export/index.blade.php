@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Équipements</a></li>
                    <li class="breadcrumb-item active">Import/Export</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-file-import text-primary"></i>
                    Importation et Exportation d'Équipements
                </h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Section Importation -->
            @if(auth()->user()->hasPermission('create_equipment'))
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-import me-2"></i>
                        Importation d'Équipements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-info-circle text-info me-2"></i>Instructions</h6>
                            <ul class="text-muted">
                                <li>Téléchargez d'abord le modèle pour connaître le format attendu</li>
                                <li>Formats supportés: Excel (.xlsx, .xls), CSV</li>
                                <li>Taille maximale: 10MB</li>
                                <li>Les colonnes obligatoires sont marquées d'un astérisque (*)</li>
                                <li>Les numéros de série doivent être uniques</li>
                            </ul>

                            <div class="d-grid gap-2">
                                <a href="{{ route('import-export.download-template') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-download me-2"></i>Télécharger le Modèle
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <form action="{{ route('import-export.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="file" class="form-label">
                                        <i class="fas fa-file-excel me-2 text-success"></i>Fichier à importer *
                                    </label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                           id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Formats acceptés: .xlsx, .xls, .csv (Max: 10MB)
                                    </div>
                                </div>

                                <!-- Aperçu des données -->
                                <div id="previewSection" class="mb-3 d-none">
                                    <h6><i class="fas fa-eye me-2"></i>Aperçu des données</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered" id="previewTable">
                                            <thead>
                                                <tr>
                                                    <th>Nom</th>
                                                    <th>N° Série</th>
                                                    <th>Catégorie</th>
                                                    <th>Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <small class="text-muted" id="previewInfo"></small>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="button" id="previewBtn" class="btn btn-info">
                                        <i class="fas fa-eye me-2"></i>Prévisualiser
                                    </button>
                                    <button type="submit" class="btn btn-success" id="importBtn" disabled>
                                        <i class="fas fa-upload me-2"></i>Importer les Données
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Section Exportation -->
            @if(auth()->user()->hasPermission('view_equipment'))
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-export me-2"></i>
                        Exportation d'Équipements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-download text-success me-2"></i>Exporter les données</h6>
                            <p class="text-muted">
                                Téléchargez la liste des équipements dans le format de votre choix.
                                Vous pouvez exporter tous les équipements ou seulement ceux opérationnels.
                            </p>
                        </div>

                        <div class="col-md-6">
                            <form action="{{ route('import-export.export') }}" method="POST" id="exportForm">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="format" class="form-label">Format *</label>
                                            <select class="form-select" id="format" name="format" required>
                                                <option value="xlsx">Excel (.xlsx)</option>
                                                <option value="csv">CSV (.csv)</option>
                                                <option value="pdf">PDF (.pdf)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="include_all" class="form-label">Équipements</label>
                                            <select class="form-select" id="include_all" name="include_all">
                                                <option value="0">Opérationnels seulement</option>
                                                <option value="1">Tous les équipements</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-download me-2"></i>Exporter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Affichage des erreurs d'importation -->
            @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="card mt-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreurs d'Importation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Ligne</th>
                                    <th>Données</th>
                                    <th>Erreurs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(session('import_errors') as $error)
                                <tr>
                                    <td class="text-danger fw-bold">Ligne {{ $error['row'] }}</td>
                                    <td>
                                        <small>
                                            @foreach($error['data'] as $key => $value)
                                                <strong>{{ $key }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </small>
                                    </td>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach($error['errors'] as $err)
                                            <li class="text-danger">{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const previewBtn = document.getElementById('previewBtn');
    const importBtn = document.getElementById('importBtn');
    const previewSection = document.getElementById('previewSection');
    const previewTable = document.getElementById('previewTable');
    const previewInfo = document.getElementById('previewInfo');

    // Prévisualisation du fichier
    previewBtn.addEventListener('click', function() {
        const file = fileInput.files[0];
        if (!file) {
            alert('Veuillez sélectionner un fichier d\'abord.');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Afficher le chargement
        previewBtn.disabled = true;
        previewBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Chargement...';

        fetch('{{ route("import-export.preview") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher l'aperçu
                displayPreview(data.preview);
                previewInfo.textContent = `Aperçu des 5 premières lignes sur ${data.total_rows} au total`;
                previewSection.classList.remove('d-none');
                importBtn.disabled = false;
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la prévisualisation');
        })
        .finally(() => {
            previewBtn.disabled = false;
            previewBtn.innerHTML = '<i class="fas fa-eye me-2"></i>Prévisualiser';
        });
    });

    function displayPreview(data) {
        const tbody = previewTable.querySelector('tbody');
        tbody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.name || row.nom || ''}</td>
                <td>${row.serial_number || row.numero_serie || ''}</td>
                <td>${row.category || row.categorie || ''}</td>
                <td>${row.status || row.statut || ''}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Confirmation avant import
    document.getElementById('importForm').addEventListener('submit', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir importer ces données ? Cette action peut écraser les équipements existants avec les mêmes numéros de série.')) {
            e.preventDefault();
        }
    });

    // Confirmation avant export
    document.getElementById('exportForm').addEventListener('submit', function(e) {
        const format = document.getElementById('format').value;
        const includeAll = document.getElementById('include_all').value;
        
        let message = `Êtes-vous sûr de vouloir exporter les équipements au format ${format.toUpperCase()} ?`;
        if (includeAll === '0') {
            message += '\n(Équipements opérationnels seulement)';
        } else {
            message += '\n(Tous les équipements)';
        }
        
        if (!confirm(message)) {
            e.preventDefault();
        }
    });
});
</script>
@endpush