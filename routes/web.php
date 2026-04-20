<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Roles\Index as RolesIndex;
use App\Livewire\Admin\Departments\Index as DepartmentsIndex;
use App\Livewire\Admin\DocumentTypes\Index as DocumentTypesIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/admin/roles', RolesIndex::class)->name('roles.index');

Route::get('/admin/departments', DepartmentsIndex::class)->name('departments.index');

Route::get('/admin/documentTypes', DocumentTypesIndex::class)->name('documentTypes.index');

Route::get('/admin/users', UsersIndex::class)->name('users.index');