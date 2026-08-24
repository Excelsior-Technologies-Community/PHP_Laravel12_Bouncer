<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function index()
    {
        $menus = Menu::root()->with('children')->get();

        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $parentMenus = Menu::root()->get();

        return view('menus.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug',
            'icon' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
            'permission' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:menus,id',
            'is_active' => 'boolean',
        ]);

        $menu = Menu::create($validated);

        $this->auditLogService->log('menu_created', $menu, null, $menu->toArray());

        return redirect()->route('menus.index')->with('success', 'Menu created successfully');
    }

    public function edit(Menu $menu)
    {
        $parentMenus = Menu::root()->where('id', '!=', $menu->id)->get();

        return view('menus.edit', compact('menu', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $menu->id,
            'icon' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
            'permission' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:menus,id',
            'is_active' => 'boolean',
        ]);

        $oldValues = $menu->toArray();
        $menu->update($validated);

        $this->auditLogService->log('menu_updated', $menu, $oldValues, $menu->fresh()->toArray());

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully');
    }

    public function destroy(Menu $menu)
    {
        $this->auditLogService->log('menu_deleted', $menu, $menu->toArray(), null);
        $menu->delete();

        return back()->with('success', 'Menu deleted successfully');
    }
}
