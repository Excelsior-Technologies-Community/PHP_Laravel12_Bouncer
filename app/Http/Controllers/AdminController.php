<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage-users')) {
            abort(403, 'Unauthorized Action');
        }

        return 'Welcome Admin';
    }
}
