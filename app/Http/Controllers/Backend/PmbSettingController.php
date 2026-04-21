<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PmbSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'pmb_end_date' => Setting::get_value('pmb_end_date', date('Y-m-d\TH:i:s', strtotime('+30 days'))),
            'pmb_gelombang' => Setting::get_value('pmb_gelombang', '2024/2025 - Gelombang 1'),
            'pmb_registration_link' => Setting::get_value('pmb_registration_link', route('pmb.register')),
            'pmb_is_open' => Setting::get_value('pmb_is_open', '1'),
            'pmb_start_date' => Setting::get_value('pmb_start_date', date('Y-m-d\TH:i:s', strtotime('+40 days'))),
            'pmb_status_link' => Setting::get_value('pmb_status_link', route('pmb.status')),
            'pmb_is_active' => Setting::get_value('pmb_is_active', '1'),
            'pmb_description' => Setting::get_value('pmb_description', ''),
        ];

        return view('backend.admin.pmb.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'pmb_end_date' => 'required|date',
            'pmb_gelombang' => 'required|string|max:255',
            'pmb_registration_link' => 'required|string|max:255',
            'pmb_status_link' => 'required|string|max:255',
            'pmb_is_open' => 'required|in:0,1',
            'pmb_start_date' => 'required|date',
            'pmb_is_active' => 'required|boolean',
            'pmb_description' => 'nullable|string',
        ]);

        Setting::set_value('pmb_end_date', $request->pmb_end_date, 'datetime');
        Setting::set_value('pmb_gelombang', $request->pmb_gelombang, 'string');
        Setting::set_value('pmb_registration_link', $request->pmb_registration_link, 'string');
        Setting::set_value('pmb_status_link', $request->pmb_status_link, 'string');
        Setting::set_value('pmb_is_open', $request->pmb_is_open, 'boolean');
        Setting::set_value('pmb_start_date', $request->pmb_start_date, 'datetime');
        Setting::set_value('pmb_is_active', $request->pmb_is_active, 'boolean');
        Setting::set_value('pmb_description', $request->pmb_description, 'string');

        return redirect()->back()->with('success', 'Pengaturan PMB berhasil diperbarui.');
    }
}
