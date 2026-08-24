<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Role;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogService;
use App\Services\AuditLogService;

class UserController extends Controller
{
    protected $activityLogService;
    protected $auditLogService;

    public function __construct(ActivityLogService $activityLogService, AuditLogService $auditLogService)
    {
        $this->activityLogService = $activityLogService;
        $this->auditLogService = $auditLogService;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $role = $request->role;
        $tenant = $request->tenant;

        $users = User::query()
            ->with(['tenant', 'roles'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($role, function ($query) use ($role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->when($tenant, function ($query) use ($tenant) {
                $query->where('tenant_id', $tenant);
            })
            ->paginate(10)
            ->withQueryString();

        $roles = Bouncer::role()->get();
        $tenants = Tenant::active()->get();

        return view('users.index', compact('users', 'search', 'status', 'role', 'tenant', 'roles', 'tenants'));
    }

    public function create()
    {
        $roles = Bouncer::role()->get();
        $tenants = Tenant::active()->get();

        return view('users.create', compact('roles', 'tenants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive,suspended',
            'tenant_id' => 'nullable|exists:tenants,id',
            'roles' => 'nullable|array',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($validated);

        if (!empty($validated['roles'])) {
            foreach ($validated['roles'] as $roleName) {
                Bouncer::assign($roleName)->to($user);
            }
        }

        $this->activityLogService->log('user_created', $user, null, $user->toArray());
        $this->auditLogService->log('created', $user, null, $user->toArray());

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        $user->load(['tenant', 'roles', 'activityLogs', 'auditLogs']);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Bouncer::role()->get();
        $tenants = Tenant::active()->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('users.edit', compact('user', 'roles', 'tenants', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'status' => 'required|in:active,inactive,suspended',
            'tenant_id' => 'nullable|exists:tenants,id',
            'roles' => 'nullable|array',
            'avatar' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $oldValues = $user->toArray();

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        if (!empty($validated['roles'])) {
            Bouncer::sync($user)->roles($validated['roles']);
        } else {
            Bouncer::sync($user)->roles([]);
        }

        $this->activityLogService->log('user_updated', $user, $oldValues, $user->fresh()->toArray());
        $this->auditLogService->log('updated', $user, $oldValues, $user->fresh()->toArray());

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('user_ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No users selected');
        }

        $users = User::whereIn('id', $ids)->get();

        foreach ($users as $user) {
            $this->activityLogService->log('user_deleted', $user, $user->toArray(), null);
            $this->auditLogService->log('deleted', $user, $user->toArray(), null);
            $user->delete();
        }

        return back()->with('success', count($users) . ' users deleted successfully');
    }

    public function bulkAssignRole(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();

        foreach ($users as $user) {
            Bouncer::allow($user)->to($request->role);
            $this->activityLogService->log('role_assigned', $user, null, ['role' => $request->role]);
            $this->auditLogService->log('role_assigned', $user, null, ['role' => $request->role]);
        }

        return back()->with('success', 'Role assigned to ' . count($users) . ' users');
    }

    public function bulkRemoveRole(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();

        foreach ($users as $user) {
            Bouncer::disallow($user)->to($request->role);
            $this->activityLogService->log('role_removed', $user, ['role' => $request->role], null);
            $this->auditLogService->log('role_removed', $user, ['role' => $request->role], null);
        }

        return back()->with('success', 'Role removed from ' . count($users) . ' users');
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $oldStatus = $user->status;
        $user->update(['status' => $request->status]);

        $this->activityLogService->log('status_changed', $user, ['status' => $oldStatus], ['status' => $request->status]);
        $this->auditLogService->log('status_changed', $user, ['status' => $oldStatus], ['status' => $request->status]);

        return back()->with('success', 'User status updated');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        $this->activityLogService->log('user_restored', $user, null, $user->toArray());
        $this->auditLogService->log('restored', $user, null, $user->toArray());

        return back()->with('success', 'User restored successfully');
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();

        return back()->with('success', 'User permanently deleted');
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->paginate(10);

        return view('users.trashed', compact('users'));
    }
}
