<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Parc Informatique')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background: #495057;
            border-left: 3px solid #0d6efd;
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background: #495057;
            border-left: 3px solid #0d6efd;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .sidebar-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            padding-top: 1rem;
        }
        
        .navbar-brand {
            padding: 1rem;
            color: #fff !important;
            border-bottom: 1px solid #495057;
        }
        
        .content {
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        /* Pour les sous-menus */
        .submenu {
            margin-left: 1.5rem;
            border-left: 1px solid #495057;
        }
        
        .submenu .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            .sidebar-sticky {
                height: auto;
            }
        }
        
        .pagination-buttons .btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.pagination-buttons .btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.pagination-buttons .btn-outline-primary {
    border-color: #0d6efd;
    color: #0d6efd;
}

.pagination-buttons .btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.pagination-buttons .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.pagination-buttons .btn-light {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
    min-width: 80px;
}

/* Responsive */
@media (max-width: 768px) {
    .pagination-buttons .d-flex {
        justify-content: center !important;
        text-align: center;
    }
    
    .pagination-buttons .text-muted {
        order: 3;
        width: 100%;
        margin-top: 1rem;
    }
}

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-sticky">
                    <!-- Logo/Brand -->
                    <div class="navbar-brand">
                        @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo" 
                         class="logo-img" 
                         style="height: 35px; width: auto;">
                @else
                    <div class="logo-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 70px; height: 50px;">
                        <i class="fas fa-laptop-code text-white"></i>
                    </div>
                @endif
                    </div>
                    
                    <!-- Navigation Menu -->
                    <ul class="nav flex-column mt-3">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Tableau de Bord
                            </a>
                        </li>
                        
                        <!-- Équipements -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('equipment.*') ? 'active' : '' }}" 
                               href="{{ route('equipment.index') }}">
                                <i class="fas fa-desktop"></i>
                                Équipements
                            </a>
                        </li>
                        
                        <!-- Maintenances -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('maintenance.*') ? 'active' : '' }}" 
                               href="{{ route('maintenance.index') }}">
                                <i class="fas fa-tools"></i>
                                Maintenances
                            </a>
                        </li>
                         @if(auth()->user()->hasPermission('create_equipment') || auth()->user()->hasPermission('view_equipment'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('import-export.index') }}">
                            <i class="fas fa-file-import"></i> Import/Export
                        </a>
                    </li>
                    @endif

                     @if(auth()->user()->hasPermission('manage_users'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('permissions.index') }}">
                            <i class="fas fa-key"></i> Permissions
                        </a>
                    </li>
                    @endif
                        
                        <!-- Utilisateurs (si permission) -->
                        @if(auth()->user()->hasPermission('view_users'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="fas fa-users"></i> Utilisateurs
                        </a>
                    </li>
                    @endif
                        
                        <!-- Rapports (si permission) -->
                        @can('view_reports')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('reports.*') ? 'active' : '' }}" 
                               href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-bar"></i>
                                Rapports
                            </a>
                        </li>
                        @endcan
                        
                        <!-- Séparateur -->
                        <li class="nav-item mt-3">
                            <hr class="border-secondary">
                        </li>
                        
                        <!-- Administration -->
                        @can('access_admin')
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="#">
                                <i class="fas fa-cogs"></i>
                                Administration
                            </a>
                            <ul class="nav flex-column submenu">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-tags"></i>
                                        Catégories
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-user-shield"></i>
                                        Rôles & Permissions
                                    </a>
                                </li>
                               <!--  <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-cog"></i>
                                        Paramètres
                                    </a>
                                </li> -->
                            </ul>
                        </li>
                        @endcan
                    </ul>
                    
                    <!-- User Info en bas -->
                    <!-- <div class="position-absolute bottom-0 start-0 end-0 p-3 border-top border-secondary">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle fa-2x text-light"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-light small">{{ auth()->user()->name }}</div>
                                <div class="text-muted small">
                                    @foreach(auth()->user()->roles as $role)
                                        <span class="badge bg-secondary">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <!-- Top Navigation Bar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
                    <div class="container-fluid">
                        <!-- Toggle Sidebar Button -->
                        <!-- <button class="btn btn-outline-secondary me-2" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#sidebar">
                            <i class="fas fa-bars"></i>
                        </button> -->
                        
                        <!-- Page Title -->
                        <span class="navbar-brand mb-0 h1">
                            @yield('page-title', 'Tableau de Bord')
                        </span>
                        
                        <!-- Right Menu -->
                        <div class="d-flex">
                            <!-- Notifications -->
                            <!-- <div class="dropdown me-3">
                                <button class="btn btn-outline-secondary position-relative" 
                                        type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-bell"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        3
                                    </span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Maintenance prévue demain</a></li>
                                    <li><a class="dropdown-item" href="#">2 équipements en panne</a></li>
                                    <li><a class="dropdown-item" href="#">Rapport mensuel disponible</a></li>
                                </ul>
                            </div> -->
                            
                            <!-- User Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" 
                                        type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-1"></i>
                                    {{ auth()->user()->name }}
                                </button>
                                <ul class="dropdown-menu">
                                    <!-- <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-user me-2"></i>Profil
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-cog me-2"></i>Paramètres
                                    </a></li> -->
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                
                <!-- Main Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Gestion responsive de la sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.querySelector('[data-bs-target="#sidebar"]');
            
            // Fermer la sidebar sur mobile après clic sur un lien
            if (window.innerWidth < 768) {
                const navLinks = sidebar.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        const bsCollapse = new bootstrap.Collapse(sidebar);
                        bsCollapse.hide();
                    });
                });
            }
            
            // Marquer l'élément actif
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            navLinks.forEach(link => {
                if (link.href === window.location.href) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>