<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $action = $request->action;
        $user = $request->user;

        $logs = ActivityLog::query()
            ->with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('action', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%");
            })
            ->when($action, function ($query) use ($action) {
                $query->where('action', $action);
            })
            ->when($user, function ($query) use ($user) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('name', 'like', "%{$user}%")
                        ->orWhere('email', 'like', "%{$user}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('activity_logs.index', compact('logs', 'search', 'action', 'user', 'actions'));
    }

    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);

        return view('activity_logs.show', compact('log'));
    }

    public function clear(Request $request)
    {
        $days = $request->days ?? 30;
        ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', 'Activity logs cleared');
    }
}
