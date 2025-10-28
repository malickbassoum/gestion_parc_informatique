<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        $roles = Role::with(['permissions', 'users'])->get();
        $permissions = Permission::with('roles')->get();
        
        return view('permissions.index', compact('roles', 'permissions'));
    }

    public function updateRolePermissions(Request $request, Role $role)
{
    if (!auth()->user()->hasPermission('manage_users')) {
        if ($request->ajax()) {
            return response()->json(['error' => 'Accès non autorisé.'], 403);
        }
        abort(403, 'Accès non autorisé.');
    }

    $request->validate([
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id'
    ]);

    try {
        $role->permissions()->sync($request->permissions ?? []);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permissions mises à jour pour le rôle ' . $role->name
            ]);
        }

        return redirect()->route('permissions.index')
            ->with('success', 'Permissions mises à jour pour le rôle ' . $role->name);

    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des permissions'
            ], 500);
        }

        return redirect()->route('permissions.index')
            ->with('error', 'Erreur lors de la mise à jour des permissions');
    }
}

    public function createRole()
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        return view('permissions.create-role');
    }

    public function storeRole(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string|max:500'
        ]);

        Role::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    public function editRole(Role $role)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        return view('permissions.edit-role', compact('role'));
    }

    public function updateRole(Request $request, Role $role)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500'
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroyRole(Role $role)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        // Empêcher la suppression des rôles par défaut
        if (in_array($role->name, ['admin', 'technician', 'user'])) {
            return redirect()->route('permissions.index')
                ->with('error', 'Impossible de supprimer les rôles par défaut.');
        }

        // Vérifier si des utilisateurs ont encore ce rôle
        if ($role->users()->count() > 0) {
            return redirect()->route('permissions.index')
                ->with('error', 'Impossible de supprimer ce rôle car des utilisateurs y sont encore assignés.');
        }

        $role->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }

    public function createPermission()
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        return view('permissions.create-permission');
    }

    public function storePermission(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'description' => 'required|string|max:500'
        ]);

        Permission::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission créée avec succès.');
    }

    public function editPermission(Permission $permission)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        return view('permissions.edit-permission', compact('permission'));
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'required|string|max:500'
        ]);

        $permission->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission mise à jour avec succès.');
    }

    public function destroyPermission(Permission $permission)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérifier si la permission est utilisée par des rôles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permissions.index')
                ->with('error', 'Impossible de supprimer cette permission car elle est assignée à des rôles.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission supprimée avec succès.');
    }
}