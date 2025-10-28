<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController; // Ajouter cette ligne
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Équipements
    Route::resource('equipment', EquipmentController::class);
    
    // Maintenances
    Route::resource('maintenance', MaintenanceController::class);
    Route::post('/maintenance/{maintenance}/start', [MaintenanceController::class, 'start'])->name('maintenance.start');
    Route::post('/maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    
    // Utilisateurs
    Route::resource('users', UserController::class);
    
    // Gestion des permissions
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('index');
    
    // Routes pour les rôles
    Route::get('/roles/create', [PermissionController::class, 'createRole'])->name('create-role');
    Route::post('/roles', [PermissionController::class, 'storeRole'])->name('store-role');
    Route::get('/roles/{role}/edit', [PermissionController::class, 'editRole'])->name('edit-role');
    Route::put('/roles/{role}', [PermissionController::class, 'updateRole'])->name('update-role');
    Route::delete('/roles/{role}', [PermissionController::class, 'destroyRole'])->name('destroy-role');
    
    // Routes pour les permissions
    Route::get('/permissions/create', [PermissionController::class, 'createPermission'])->name('create-permission');
    Route::post('/permissions', [PermissionController::class, 'storePermission'])->name('store-permission');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'editPermission'])->name('edit-permission');
    Route::put('/permissions/{permission}', [PermissionController::class, 'updatePermission'])->name('update-permission');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroyPermission'])->name('destroy-permission');
    
    // Mise à jour des permissions des rôles (ATTENTION: bien nommer cette route)
    Route::put('/roles/{role}/permissions', [PermissionController::class, 'updateRolePermissions'])->name('update-role-permissions');
    });
});

require __DIR__.'/auth.php';