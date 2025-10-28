@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-key text-primary"></i>
                    Gestion des Rôles et Permissions
                </h1>
                <div class="btn-group">
                    <a href="{{ route('permissions.create-role') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouveau Rôle
                    </a>
                    <a href="{{ route('permissions.create-permission') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nouvelle Permission
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Tableau des rôles et permissions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users-cog"></i>
                        Attribution des Permissions par Rôle
                    </h5>
                </div>
                <div class="card-body">
                    @if($permissions->isEmpty() || $roles->isEmpty())
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle"></i>
                            Aucune permission ou rôle configuré. Commencez par créer des permissions et des rôles.
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Rôle / Permission</th>
                                    @foreach($permissions as $permission)
                                    <th class="text-center" title="{{ $permission->description }}">
                                        <div class="d-flex flex-column">
                                            <small class="fw-bold">{{ $permission->name }}</small>
                                            <small class="text-muted">{{ Str::limit($permission->description, 15) }}</small>
                                        </div>
                                    </th>
                                    @endforeach
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <strong>{{ $role->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $role->description }}</small>
                                        <br>
                                        <small class="text-info">
                                            {{ $role->users->count() }} utilisateur(s)
                                        </small>
                                    </td>
                                    
                                    @foreach($permissions as $permission)
                                    <td class="text-center align-middle">
                                        <form action="{{ route('permissions.update-role-permissions', $role) }}" method="POST" class="permission-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input permission-checkbox" 
                                                       type="checkbox" 
                                                       name="permission_active"
                                                       value="1"
                                                       {{ $role->permissions->contains($permission) ? 'checked' : '' }}
                                                       data-role-id="{{ $role->id }}"
                                                       data-permission-id="{{ $permission->id }}">
                                            </div>
                                        </form>
                                    </td>
                                    @endforeach
                                    
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('permissions.edit-role', $role) }}" class="btn btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!in_array($role->name, ['admin', 'technician', 'user']))
                                            <form action="{{ route('permissions.destroy-role', $role) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Liste des permissions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i>
                        Liste des Permissions
                    </h5>
                </div>
                <div class="card-body">
                    @if($permissions->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i>
                            Aucune permission créée. 
                            <a href="{{ route('permissions.create-permission') }}" class="alert-link">Créez la première permission</a>.
                        </div>
                    @else
                    <div class="row">
                        @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                        <div class="col-md-4">
                            @foreach($chunk as $permission)
                            <div class="card mb-2">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 text-primary">{{ $permission->name }}</h6>
                                            <small class="text-muted">{{ $permission->description }}</small>
                                            <br>
                                            <small class="text-info">
                                                {{ $permission->roles->count() }} rôle(s)
                                            </small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('permissions.edit-permission', $permission) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($permission->roles->count() == 0)
                                            <form action="{{ route('permissions.destroy-permission', $permission) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette permission ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.permission-checkbox {
    transform: scale(1.3);
    cursor: pointer;
}
.form-switch .form-check-input {
    width: 3em;
}
.table th {
    vertical-align: middle;
    font-size: 0.85rem;
}
.loading {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des switches de permissions
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const form = this.closest('permission-form');
            const roleId = this.getAttribute('data-role-id');
            const permissionId = this.getAttribute('data-permission-id');
            const isChecked = this.checked;
            
            // Feedback visuel
            this.disabled = true;
            this.parentElement.classList.add('loading');
            
            // Créer les données à envoyer
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');
            
            // Construire le tableau des permissions
            let permissions = [];
            document.querySelectorAll(`.permission-checkbox[data-role-id="${roleId}"]`).forEach(cb => {
                if (cb.checked) {
                    permissions.push(cb.getAttribute('data-permission-id'));
                }
            });
            
            // Ajouter toutes les permissions cochées pour ce rôle
            permissions.forEach(permId => {
                formData.append('permissions[]', permId);
            });
            
            // Envoyer la requête
            fetch(`/permissions/roles/${roleId}/permissions`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                // Succès - restaurer l'état du checkbox
                this.checked = isChecked;
                
                // Afficher un message de succès temporaire
                showNotification('Permissions mises à jour avec succès', 'success');
            })
            .catch(error => {
                // Erreur - remettre le checkbox à son état précédent
                this.checked = !isChecked;
                console.error('Erreur:', error);
                showNotification('Erreur lors de la mise à jour', 'error');
            })
            .finally(() => {
                // Restaurer l'interface
                this.disabled = false;
                this.parentElement.classList.remove('loading');
            });
        });
    });
    
    function showNotification(message, type) {
        // Créer une notification temporaire
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Supprimer après 3 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }
    
    // Amélioration de l'UX : tooltips sur les permissions
    const permissionHeaders = document.querySelectorAll('thead th[title]');
    permissionHeaders.forEach(header => {
        header.setAttribute('data-bs-toggle', 'tooltip');
    });
    
    // Initialiser les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection