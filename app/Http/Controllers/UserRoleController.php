<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserRoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('roles', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('title', 'like', "%{$search}%");
                    });
            })
            ->paginate(4)
            ->withQueryString();

        $roles = Bouncer::role()->get();

        return view('user_roles.index', compact('users', 'roles', 'search'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'role' => 'required'
        ]);

        $user = User::findOrFail($request->user_id);

        Bouncer::sync($user)->roles([$request->role]);

        return back()->with('success', 'Role assigned successfully');
    }
}