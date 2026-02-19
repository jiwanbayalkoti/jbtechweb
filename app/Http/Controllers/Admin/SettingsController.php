<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::getValue(null, 'general', 'site_name', config('app.name')),
            'support_email' => Setting::getValue(null, 'general', 'support_email', ''),
            'default_currency' => Setting::getValue(null, 'general', 'default_currency', 'USD'),
            'logo' => Setting::getValue(null, 'general', 'logo', ''),
        ];
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'support_email' => 'nullable|email',
            'default_currency' => 'required|string|max:5',
            'logo' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::getValue(null, 'general', 'logo', '');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('logo')->store('logo', 'public');
            Setting::setValue(null, 'general', 'logo', $path);
        }

        unset($validated['logo']);
        foreach ($validated as $key => $value) {
            Setting::setValue(null, 'general', $key, $value);
        }

        return response()->json(['success' => true, 'message' => 'Settings updated successfully']);
    }
}
