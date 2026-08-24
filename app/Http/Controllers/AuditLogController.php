<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $event = $request->event;
        $user = $request->user;

        $logs = AuditLog::query()
            ->with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('event', 'like', "%{$search}%")
                    ->orWhere('auditable_type', 'like', "%{$search}%");
            })
            ->when($event, function ($query) use ($event) {
                $query->where('event', $event);
            })
            ->when($user, function ($query) use ($user) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('name', 'like', "%{$user}%")
                        ->orWhere('email', 'like', "%{$user}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        $events = AuditLog::select('event')->distinct()->pluck('event');

        return view('audit_logs.index', compact('logs', 'search', 'event', 'user', 'events'));
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);

        return view('audit_logs.show', compact('log'));
    }

    public function clear(Request $request)
    {
        $days = $request->days ?? 30;
        AuditLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', 'Audit logs cleared');
    }
}
