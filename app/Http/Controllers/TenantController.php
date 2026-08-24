<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $tenants = Tenant::query()
            ->withCount('users')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('tenants.index', compact('tenants', 'search'));
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug',
            'domain' => 'nullable|string|max:255|unique:tenants,domain',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'settings' => 'nullable|array',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('tenant-logos', 'public');
        }

        $tenant = Tenant::create($validated);

        $this->auditLogService->log('tenant_created', $tenant, null, $tenant->toArray());

        return redirect()->route('tenants.index')->with('success', 'Tenant created successfully');
    }

    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug,' . $tenant->id,
            'domain' => 'nullable|string|max:255|unique:tenants,domain,' . $tenant->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'settings' => 'nullable|array',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $oldValues = $tenant->toArray();

        if ($request->hasFile('logo')) {
            if ($tenant->logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tenant->logo);
            }
            $validated['logo'] = $request->file('logo')->store('tenant-logos', 'public');
        }

        $tenant->update($validated);

        $this->auditLogService->log('tenant_updated', $tenant, $oldValues, $tenant->fresh()->toArray());

        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully');
    }

    public function destroy(Tenant $tenant)
    {
        $this->auditLogService->log('tenant_deleted', $tenant, $tenant->toArray(), null);
        $tenant->delete();

        return back()->with('success', 'Tenant deleted successfully');
    }
}
