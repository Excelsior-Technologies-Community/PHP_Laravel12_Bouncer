<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Bouncer::role()->get();

        return view('user_roles.index', compact('users','roles'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'role' => 'required'
        ]);

        $user = User::findOrFail($request->user_id);

        Bouncer::sync($user)->roles([$request->role]);

        return back()->with('success','Role assigned successfully');
    }
}