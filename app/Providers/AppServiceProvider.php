<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use App\Models\User;
use App\Models\Menu;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ActivityLogObserver;
use App\Observers\AuditLogObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Load settings from database
        Setting::addGlobalScope('loaded', function ($builder) {
            $builder->where('group', 'general');
        });

        // Auto-create notification preferences for new users
        User::created(function ($user) {
            NotificationPreference::create([
                'user_id' => $user->id,
                'email_notifications' => ['user_created', 'role_assigned', 'user_suspended'],
                'database_notifications' => ['user_created', 'role_assigned', 'user_suspended'],
                'realtime_notifications' => ['user_created', 'role_assigned', 'user_suspended'],
            ]);
        });
    }
}
