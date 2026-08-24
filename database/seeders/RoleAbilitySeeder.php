<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Models\User;
use App\Models\Tenant;

class RoleAbilitySeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $admin = Bouncer::role()->firstOrCreate([
            'name' => 'admin',
            'title' => 'Administrator',
        ]);

        $user = Bouncer::role()->firstOrCreate([
            'name' => 'user',
            'title' => 'User',
        ]);

        $manager = Bouncer::role()->firstOrCreate([
            'name' => 'manager',
            'title' => 'Manager',
        ]);

        // Abilities
        $abilities = [
            ['name' => 'manage-users', 'title' => 'Manage Users'],
            ['name' => 'view-dashboard', 'title' => 'View Dashboard'],
            ['name' => 'manage-roles', 'title' => 'Manage Roles'],
            ['name' => 'manage-tenants', 'title' => 'Manage Tenants'],
            ['name' => 'manage-settings', 'title' => 'Manage Settings'],
            ['name' => 'manage-menus', 'title' => 'Manage Menus'],
            ['name' => 'view-activity-logs', 'title' => 'View Activity Logs'],
            ['name' => 'view-audit-logs', 'title' => 'View Audit Logs'],
            ['name' => 'impersonate-users', 'title' => 'Impersonate Users'],
            ['name' => 'export-data', 'title' => 'Export Data'],
        ];

        foreach ($abilities as $ability) {
            Bouncer::ability()->firstOrCreate($ability);
        }

        // Assign abilities to admin
        Bouncer::allow($admin)->to(array_column($abilities, 'name'));

        // Assign abilities to manager
        Bouncer::allow($manager)->to(['view-dashboard', 'manage-users', 'view-activity-logs', 'view-audit-logs', 'export-data']);

        // Assign abilities to user
        Bouncer::allow($user)->to(['view-dashboard']);

        // Create default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'status' => 'active',
            ]
        );

        // Assign role to first user
        $firstUser = User::first();
        if ($firstUser) {
            Bouncer::assign('admin')->to($firstUser);
        }

        // Ensure admin user has admin role
        if ($adminUser && $adminUser->id !== $firstUser->id) {
            Bouncer::assign('admin')->to($adminUser);
        }

        // Create demo tenant
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'demo-tenant'],
            [
                'name' => 'Demo Tenant',
                'domain' => 'demo.localhost',
                'email' => 'demo@example.com',
                'phone' => '+1234567890',
                'status' => 'active',
            ]
        );
    }
}
