<?php

use Illuminate\Support\Facades\Route;

// Admin
use App\Livewire\Admin\Roles\Index as RolesIndex;
use App\Livewire\Admin\Departments\Index as DepartmentsIndex;
use App\Livewire\Admin\DocumentTypes\Index as DocumentTypesIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;

// Documents
use App\Livewire\Documents\PublicIndex;
use App\Livewire\Documents\Index as DocumentsIndex;
use App\Livewire\Documents\Create as DocumentsCreate;
use App\Livewire\Documents\Versions as DocumentsVersions;

// ── Rutas públicas ────────────────────────────────────────────
Route::view('/', 'welcome');

// ── Rutas autenticadas ────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // ── Empleados — cards públicas ────────────────────────────
    Route::get('/documentos', PublicIndex::class)
        ->name('documents.public');

    // ── Gestión de documentos (supervisor, committee, admin) ──
    Route::middleware(['role:supervisor,committee,admin'])->group(function () {

        Route::get('/gestion/documentos', DocumentsIndex::class)
            ->name('documents.index');

        Route::get('/gestion/documentos/crear', DocumentsCreate::class)
            ->name('documents.create');

        Route::get('/gestion/documentos/{document}/versiones', DocumentsVersions::class)
            ->name('documents.versions');
    });

    // ── Admin — solo admin ────────────────────────────────────
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {

        Route::get('/roles', RolesIndex::class)
            ->name('roles.index');

        Route::get('/departments', DepartmentsIndex::class)
            ->name('departments.index');

        Route::get('/document-types', DocumentTypesIndex::class)
            ->name('documentTypes.index');

        Route::get('/users', UsersIndex::class)
            ->name('users.index');
    });
});

require __DIR__ . '/auth.php';