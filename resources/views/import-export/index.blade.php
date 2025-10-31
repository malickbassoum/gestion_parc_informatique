<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import/Export - Gestion Parc Informatique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-laptop-house"></i> Gestion Parc Informatique
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/equipment">Équipements</a></li>
                        <li class="breadcrumb-item active">Import/Export</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>
                        <i class="fas fa-file-import text-primary"></i>
                        Importation et Exportation d'Équipements
                    </h1>
                </div>

                <!-- Messages de session -->
                <?php
                if(session('success')) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>'.session('success').'
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                          </div>';
                }
                if(session('error')) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle me-2"></i>'.session('error').'
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                          </div>';
                }
                ?>

                <!-- Section Importation -->
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
                                    <li>Les colonnes obligatoires: name, serial_number, category, brand, model, status, location</li>
                                </ul>

                                <div class="d-grid gap-2 mb-3">
                                    <a href="/import-export/download-template" class="btn btn-outline-primary">
                                        <i class="fas fa-download me-2"></i>Télécharger le Modèle
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <form action="/import-export/import" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    
                                    <div class="mb-3">
                                        <label for="file" class="form-label">
                                            <i class="fas fa-file-excel me-2 text-success"></i>Fichier à importer *
                                        </label>
                                        <input type="file" class="form-control" 
                                               id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                        <div class="form-text">
                                            Formats acceptés: .xlsx, .xls, .csv (Max: 10MB)
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-upload me-2"></i>Importer les Données
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Exportation -->
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
                                </p>
                            </div>

                            <div class="col-md-6">
                                <form action="/import-export/export" method="POST">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    
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
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-download me-2"></i>Exporter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script de test ultra basique -->
    <script>
        // Test basique - si cette alerte s'affiche, le JS fonctionne
        setTimeout(function() {
            console.log('Page Import/Export chargée avec succès');
        }, 1000);
    </script>
</body>
</html>