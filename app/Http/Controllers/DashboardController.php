<?php

namespace App\Http\Controllers;

use App\Models\User;
use Silber\Bouncer\BouncerFacade as Bouncer;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalUsers' => User::count(),
            'totalRoles' => Bouncer::role()->count(),
            'totalPermissions' => Bouncer::ability()->count(),
        ]);
    }
}