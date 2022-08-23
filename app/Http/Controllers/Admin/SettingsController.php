<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::get();
        return view('admin.settings', compact('settings'));
    }

    public function postNewSetting(Request $request)
    {
        $this->validate($request, [
            'key' => 'required|unique:settings',
            'value' => 'required',
        ]);

        $setting = Setting::create([
            'key' => $request->key,
            'value' => $request->value,
        ]);

        return response()->json([
            'setting' => $setting,
        ]);
    }

    public function postEditSetting(Request $request, Setting $setting)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required',
        ]);

        $setting->update([
            'value' => $request->value,
        ]);

        $setting->touch();

        return response()->json([
            'setting' => $setting,
        ]);
    }

    public function deleteSetting(Request $request, Setting $setting)
    {
        $setting->delete();

        return response(null, 200);
    }
}
