<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Welcome Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Protected Routes (ALL AUTH BASED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    | Profile
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    | Roles CRUD (RBAC)
    */
    Route::resource('roles', RoleController::class);

    /*
    | User Role Assignment
    */
    Route::get('/user-roles', [UserRoleController::class, 'index'])
        ->name('user.roles');

    Route::post('/user-roles/assign', [UserRoleController::class, 'assign'])
        ->name('user.roles.assign');

    /*
    | Protected Example Route (Bouncer Permission)
    */
    Route::get('/users', function () {
        return 'User Management Page';
    })->middleware('can:manage-users');

});

require __DIR__.'/auth.php';