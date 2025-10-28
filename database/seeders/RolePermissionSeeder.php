<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Créer les permissions
        $permissions = [
            ['name' => 'view_equipment', 'description' => 'Voir les équipements'],
            ['name' => 'create_equipment', 'description' => 'Créer des équipements'],
            ['name' => 'edit_equipment', 'description' => 'Modifier les équipements'],
            ['name' => 'delete_equipment', 'description' => 'Supprimer les équipements'],
            ['name' => 'view_maintenance', 'description' => 'Voir les maintenances'],
            ['name' => 'create_maintenance', 'description' => 'Créer des maintenances'],
            ['name' => 'edit_maintenance', 'description' => 'Modifier les maintenances'],
            ['name' => 'delete_maintenance', 'description' => 'Supprimer les maintenances'],
            ['name' => 'start_maintenance', 'description' => 'Démarrer les maintenances'],
            ['name' => 'complete_maintenance', 'description' => 'Terminer les maintenances'],
            ['name' => 'view_users', 'description' => 'Voir les utilisateurs'],
            ['name' => 'manage_users', 'description' => 'Gérer les utilisateurs'],
            ['name' => 'view_reports', 'description' => 'Voir les rapports'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Créer les rôles
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrateur système'
        ]);

        $technicianRole = Role::create([
            'name' => 'technician',
            'description' => 'Technicien maintenance'
        ]);

        $userRole = Role::create([
            'name' => 'user',
            'description' => 'Utilisateur standard'
        ]);

        // Attribuer les permissions aux rôles
        $adminRole->permissions()->attach(Permission::all());
        
        $technicianPermissions = Permission::whereIn('name', [
            'view_equipment',
            'view_maintenance',
            'create_maintenance',
            'edit_maintenance',
            'start_maintenance',
            'complete_maintenance'
        ])->get();
        $technicianRole->permissions()->attach($technicianPermissions);

        $userPermissions = Permission::whereIn('name', [
            'view_equipment',
            'view_maintenance'
        ])->get();
        $userRole->permissions()->attach($userPermissions);

        // Créer un utilisateur admin
        $admin = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@parcinformatique.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'department' => 'IT',
        ]);
        $admin->roles()->attach($adminRole);

        // Créer un utilisateur technicien
        $technician = User::create([
            'name' => 'Technicien',
            'email' => 'technicien@parcinformatique.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567891',
            'department' => 'Maintenance',
        ]);
        $technician->roles()->attach($technicianRole);

        // Créer un utilisateur standard
        $user = User::create([
            'name' => 'Utilisateur',
            'email' => 'user@parcinformatique.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567892',
            'department' => 'Ventes',
        ]);
        $user->roles()->attach($userRole);
    }
}