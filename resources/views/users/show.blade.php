@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-user text-primary"></i>
                    {{ $user->name }}
                </h1>
                <div class="btn-group">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    @if(auth()->user()->hasPermission('manage_users'))
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations de l'Utilisateur</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nom complet:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone:</th>
                                    <td>{{ $user->phone ?? 'Non spécifié' }}</td>
                                </tr>
                                <tr>
                                    <th>Département:</th>
                                    <td>{{ $user->department ?? 'Non spécifié' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Rôles:</th>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge 
                                                @if($role->name == 'admin') bg-danger
                                                @elseif($role->name == 'technician') bg-warning
                                                @else bg-secondary @endif mb-1">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date de création:</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière mise à jour:</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Email vérifié:</th>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-warning">Non</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions de l'utilisateur -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-key"></i> Permissions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $allPermissions = \App\Models\Permission::all();
                            $userPermissions = $user->roles->flatMap->permissions->unique('name');
                        @endphp
                        
                        @foreach($allPermissions->chunk(4) as $chunk)
                        <div class="col-md-6">
                            @foreach($chunk as $permission)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       {{ $userPermissions->contains('name', $permission->name) ? 'checked' : '' }} 
                                       disabled>
                                <label class="form-check-label">
                                    {{ $permission->description ?? $permission->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection