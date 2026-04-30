<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Roles\Index as RolesIndex;
use App\Livewire\Admin\Departments\Index as DepartmentsIndex;
use App\Livewire\Admin\DocumentTypes\Index as DocumentTypesIndex;
use App\Livewire\Supervisor\Notices\Index as NoticesIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Supervisor\DocumentManager\Index as DocumentManagerIndex;
use App\Livewire\Supervisor\DocumentVersionManager\Index as DocumentVersionManagerIndex;



Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';


Route::middleware('auth')->group(function () {
    Route::get('/admin/roles', RolesIndex::class)->name('roles.index');

    Route::get('/admin/departments', DepartmentsIndex::class)->name('departments.index');

    Route::get('/admin/documentTypes', DocumentTypesIndex::class)->name('documentTypes.index');

    Route::get('/admin/users', UsersIndex::class)->name('users.index');

    Route::get('/supervisor/notices', NoticesIndex::class)->name('notices.index');

    Route::get('/documents', DocumentManagerIndex::class)->name('documentManager.index');

    Route::get('/documents/{document}/versions', DocumentVersionManagerIndex::class)->name('documentVersionManager.index');
});
