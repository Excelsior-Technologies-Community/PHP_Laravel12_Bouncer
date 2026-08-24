<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function log(string $action, $subject = null, array $oldValues = null, array $newValues = null): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => is_object($subject) ? get_class($subject) : null,
            'subject_id' => is_object($subject) ? $subject->id : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'properties' => [
                'old_values' => $oldValues,
                'new_values' => $newValues,
            ],
            'created_at' => now(),
        ]);
    }

    public function getRecent(int $days = 7)
    {
        return ActivityLog::with('user')
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->take(50)
            ->get();
    }
}
