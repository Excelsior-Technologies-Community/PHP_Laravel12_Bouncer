<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $roles = Bouncer::role()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            })
            ->paginate(4);

        return view('roles.index', compact('roles', 'search'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'title' => 'required'
        ]);

        Bouncer::role()->create([
            'name' => $request->name,
            'title' => $request->title,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created');
    }

    public function edit($id)
    {
        $role = Bouncer::role()->findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Bouncer::role()->findOrFail($id);

        $role->update([
            'name' => $request->name,
            'title' => $request->title,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated');
    }

    public function destroy($id)
    {
        Bouncer::role()->findOrFail($id)->delete();

        return back()->with('success', 'Role deleted');
    }
}