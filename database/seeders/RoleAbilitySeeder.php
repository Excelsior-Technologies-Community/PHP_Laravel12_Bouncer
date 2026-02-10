<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Models\User;

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

        // Abilities
        Bouncer::ability()->firstOrCreate([
            'name' => 'manage-users',
            'title' => 'Manage Users',
        ]);

        Bouncer::ability()->firstOrCreate([
            'name' => 'view-dashboard',
            'title' => 'View Dashboard',
        ]);

        // Assign abilities
        Bouncer::allow($admin)->to(['manage-users', 'view-dashboard']);
        Bouncer::allow($user)->to(['view-dashboard']);

        // Assign role to first user
        $firstUser = User::first();
        if ($firstUser) {
            Bouncer::assign('admin')->to($firstUser);
        }
    }
}
