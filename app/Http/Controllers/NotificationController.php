<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use App\Models\User;
use App\Notifications\UserCreated;
use App\Notifications\RoleAssigned;
use App\Notifications\UserSuspended;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read');
    }

    public function preferences()
    {
        $preferences = Auth::user()->notificationPreference ?? new NotificationPreference(['user_id' => Auth::id()]);

        return view('notifications.preferences', compact('preferences'));
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'email_notifications' => 'nullable|array',
            'database_notifications' => 'nullable|array',
            'realtime_notifications' => 'nullable|array',
        ]);

        $preferences = NotificationPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'email_notifications' => $request->email_notifications ?? [],
                'database_notifications' => $request->database_notifications ?? [],
                'realtime_notifications' => $request->realtime_notifications ?? [],
            ]
        );

        return back()->with('success', 'Notification preferences updated');
    }

    public function sendTestNotification()
    {
        $user = Auth::user();
        $user->notify(new UserCreated($user));

        return back()->with('success', 'Test notification sent');
    }
}
