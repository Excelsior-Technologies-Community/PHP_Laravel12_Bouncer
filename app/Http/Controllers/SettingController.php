<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Tenant;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function index()
    {
        $groups = Setting::select('group')->distinct()->pluck('group');
        $settings = Setting::all()->groupBy('group');

        return view('settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $oldValue = $setting->value;
        $setting->update(['value' => $request->value]);

        $this->auditLogService->log('setting_updated', $setting, ['value' => $oldValue], ['value' => $request->value]);

        return back()->with('success', 'Setting updated successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'group' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'value' => 'required',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Setting::create($request->all());

        return back()->with('success', 'Setting created successfully');
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();

        return back()->with('success', 'Setting deleted successfully');
    }
}
