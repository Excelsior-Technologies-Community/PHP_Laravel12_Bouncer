<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ImpersonationSession;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function impersonate(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot impersonate yourself');
        }

        if (!$user->hasRole('admin') && !$user->hasRole('user')) {
            return back()->with('error', 'Invalid user');
        }

        $adminId = Auth::id();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        Auth::login($user);

        ImpersonationSession::create([
            'admin_id' => $adminId,
            'impersonated_user_id' => $user->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'started_at' => now(),
        ]);

        Session::put('impersonated_by', $adminId);

        $this->auditLogService->log('impersonation_started', $user, ['admin_id' => $adminId], null);

        return redirect()->route('dashboard')->with('success', "You are now impersonating {$user->name}");
    }

    public function leaveImpersonation()
    {
        $adminId = Session::get('impersonated_by');

        if ($adminId) {
            $admin = User::findOrFail($adminId);
            $currentUser = Auth::user();

            ImpersonationSession::where('admin_id', $adminId)
                ->where('impersonated_user_id', $currentUser->id)
                ->whereNull('ended_at')
                ->update(['ended_at' => now()]);

            $this->auditLogService->log('impersonation_ended', $currentUser, null, ['admin_id' => $adminId]);

            Session::forget('impersonated_by');
            Auth::login($admin);

            return redirect()->route('dashboard')->with('success', 'You have left impersonation mode');
        }

        return redirect()->route('dashboard')->with('error', 'You are not impersonating anyone');
    }

    public function sessions()
    {
        $sessions = ImpersonationSession::with(['admin', 'impersonatedUser'])
            ->orderByDesc('started_at')
            ->paginate(20);

        return view('impersonation.sessions', compact('sessions'));
    }
}
