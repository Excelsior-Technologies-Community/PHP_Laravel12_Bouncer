<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log(string $event, $auditable = null, array $oldValues = null, array $newValues = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => is_object($auditable) ? get_class($auditable) : null,
            'auditable_id' => is_object($auditable) ? $auditable->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    public function getRecent(int $days = 7)
    {
        return AuditLog::with('user')
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->take(50)
            ->get();
    }
}
