<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

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
    | Users Management
    */
    Route::get('/users', [UserController::class, 'index'])
        ->middleware('can:manage-users')
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->middleware('can:manage-users')
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->middleware('can:manage-users')
        ->name('users.store');

    Route::get('/users/trashed', [UserController::class, 'trashed'])
        ->middleware('can:manage-users')
        ->name('users.trashed');

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->middleware('can:manage-users')
        ->name('users.show');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->middleware('can:manage-users')
        ->name('users.edit');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('can:manage-users')
        ->name('users.update');

    Route::delete('/users', [UserController::class, 'destroy'])
        ->middleware('can:manage-users')
        ->name('users.destroy');

    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])
        ->middleware('can:manage-users')
        ->name('users.update.status');

    Route::post('/users/bulk/assign-role', [UserController::class, 'bulkAssignRole'])
        ->middleware('can:manage-users')
        ->name('users.bulk.assign.role');

    Route::post('/users/bulk/remove-role', [UserController::class, 'bulkRemoveRole'])
        ->middleware('can:manage-users')
        ->name('users.bulk.remove.role');

    Route::post('/users/{id}/restore', [UserController::class, 'restore'])
        ->middleware('can:manage-users')
        ->name('users.restore');

    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])
        ->middleware('can:manage-users')
        ->name('users.forceDelete');

    /*
    | Export
    */
    Route::get('/export/users', [ExportController::class, 'exportUsers'])
        ->middleware('can:manage-users')
        ->name('export.users');

    Route::get('/export/activity', [ExportController::class, 'exportActivityLogs'])
        ->middleware('can:view-activity-logs')
        ->name('export.activity');

    Route::get('/export/audit', [ExportController::class, 'exportAuditLogs'])
        ->middleware('can:view-audit-logs')
        ->name('export.audit');

    /*
    | Activity Logs
    */
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('can:view-activity-logs')
        ->name('activity.logs.index');

    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])
        ->middleware('can:view-activity-logs')
        ->name('activity.logs.show');

    Route::post('/activity-logs/clear', [ActivityLogController::class, 'clear'])
        ->middleware('can:view-activity-logs')
        ->name('activity.logs.clear');

    /*
    | Audit Logs
    */
    Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->middleware('can:view-audit-logs')
        ->name('audit.logs.index');

    Route::get('/audit-logs/{id}', [AuditLogController::class, 'show'])
        ->middleware('can:view-audit-logs')
        ->name('audit.logs.show');

    Route::post('/audit-logs/clear', [AuditLogController::class, 'clear'])
        ->middleware('can:view-audit-logs')
        ->name('audit.logs.clear');

    /*
    | Settings
    */
    Route::get('/settings', [SettingController::class, 'index'])
        ->middleware('can:manage-settings')
        ->name('settings.index');

    Route::post('/settings', [SettingController::class, 'store'])
        ->middleware('can:manage-settings')
        ->name('settings.store');

    Route::put('/settings/{setting}', [SettingController::class, 'update'])
        ->middleware('can:manage-settings')
        ->name('settings.update');

    Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])
        ->middleware('can:manage-settings')
        ->name('settings.destroy');

    /*
    | Notifications
    */
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markAsRead');

    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.markAllRead');

    Route::get('/notifications/preferences', [NotificationController::class, 'preferences'])
        ->name('notifications.preferences');

    Route::post('/notifications/preferences', [NotificationController::class, 'updatePreferences'])
        ->name('notifications.updatePreferences');

    Route::post('/notifications/test', [NotificationController::class, 'sendTestNotification'])
        ->name('notifications.test');

    /*
    | Impersonation
    */
    Route::post('/impersonate/{user}', [ImpersonationController::class, 'impersonate'])
        ->middleware('can:impersonate-users')
        ->name('impersonation.start');

    Route::post('/impersonation/leave', [ImpersonationController::class, 'leaveImpersonation'])
        ->name('impersonation.leave');

    Route::get('/impersonation/sessions', [ImpersonationController::class, 'sessions'])
        ->middleware('can:impersonate-users')
        ->name('impersonation.sessions');

    /*
    | Tenants
    */
    Route::get('/tenants', [TenantController::class, 'index'])
        ->middleware('can:manage-tenants')
        ->name('tenants.index');

    Route::get('/tenants/create', [TenantController::class, 'create'])
        ->middleware('can:manage-tenants')
        ->name('tenants.create');

    Route::post('/tenants', [TenantController::class, 'store'])
        ->middleware('can:manage-tenants')
        ->name('tenants.store');

    Route::get('/tenants/{tenant}/edit', [TenantController::class, 'edit'])
        ->middleware('can:manage-tenants')
        ->name('tenants.edit');

    Route::put('/tenants/{tenant}', [TenantController::class, 'update'])
        ->middleware('can:manage-tenants')
        ->name('tenants.update');

    Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])
        ->middleware('can:manage-tenants')
        ->name('tenants.destroy');

    /*
    | Menus
    */
    Route::get('/menus', [MenuController::class, 'index'])
        ->middleware('can:manage-menus')
        ->name('menus.index');

    Route::get('/menus/create', [MenuController::class, 'create'])
        ->middleware('can:manage-menus')
        ->name('menus.create');

    Route::post('/menus', [MenuController::class, 'store'])
        ->middleware('can:manage-menus')
        ->name('menus.store');

    Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])
        ->middleware('can:manage-menus')
        ->name('menus.edit');

    Route::put('/menus/{menu}', [MenuController::class, 'update'])
        ->middleware('can:manage-menus')
        ->name('menus.update');

    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])
        ->middleware('can:manage-menus')
        ->name('menus.destroy');

    /*
    | Media Upload
    */
    Route::post('/media/upload', [MediaController::class, 'upload'])
        ->name('media.upload');

    Route::delete('/media/{media}', [MediaController::class, 'destroy'])
        ->name('media.destroy');

    /*
    | Protected Example Route (Bouncer Permission)
    */
    Route::get('/users-old', function () {
        return 'User Management Page';
    })->middleware('can:manage-users');

});

require __DIR__.'/auth.php';
