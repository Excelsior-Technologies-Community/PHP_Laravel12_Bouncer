<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Services\ActivityLogService;
use App\Services\AuditLogService;

class DashboardController extends Controller
{
    protected $activityLogService;
    protected $auditLogService;

    public function __construct(ActivityLogService $activityLogService, AuditLogService $auditLogService)
    {
        $this->activityLogService = $activityLogService;
        $this->auditLogService = $auditLogService;
    }

    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::active()->count();
        $inactiveUsers = User::inactive()->count();
        $suspendedUsers = User::suspended()->count();
        $totalRoles = Bouncer::role()->count();
        $totalPermissions = Bouncer::ability()->count();
        $totalTenants = Tenant::count();
        $recentUsers = User::latest()->take(5)->get();
        $recentActivity = $this->activityLogService->getRecent(7);
        $recentAudit = $this->auditLogService->getRecent(7);

        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $roleDistribution = Bouncer::role()->get()->map(function ($role) {
            return [
                'name' => $role->title ?? $role->name,
                'count' => $role->users()->count(),
            ];
        });

        $permissionUsage = Bouncer::ability()->get()->map(function ($ability) {
            return [
                'name' => $ability->title ?? $ability->name,
                'count' => $ability->users()->count() + $ability->roles()->count(),
            ];
        })->sortByDesc('count')->take(10);

        return view('dashboard', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'suspendedUsers',
            'totalRoles',
            'totalPermissions',
            'totalTenants',
            'recentUsers',
            'recentActivity',
            'recentAudit',
            'userGrowth',
            'roleDistribution',
            'permissionUsage'
        ));
    }
}
