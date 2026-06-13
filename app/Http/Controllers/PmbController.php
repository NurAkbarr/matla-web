<?php

namespace App\Http\Controllers;

use App\Models\BrosurPmb;
use Illuminate\Http\Request;

class PmbController extends Controller
{
    public function index(Request $request)
    {
        // Tracking Affiliate
        if ($request->has('ref')) {
            session(['pmb_referrer' => $request->query('ref')]);
        }

        $pmb_is_open = \App\Models\Setting::get_value('pmb_is_open', '1');
        $pmb_end_date = \App\Models\Setting::get_value('pmb_end_date', date('Y-m-d\TH:i:s', strtotime('+30 days')));

        if (strtotime($pmb_end_date) < time()) {
            $pmb_is_open = '0';
        }

        $settings = [
            'pmb_end_date' => $pmb_end_date,
            'pmb_gelombang' => \App\Models\Setting::get_value('pmb_gelombang', '2024/2025 - Gelombang 1'),
            'pmb_registration_link' => \App\Models\Setting::get_value('pmb_registration_link', route('pmb.register')),
            'pmb_is_open' => $pmb_is_open,
            'pmb_start_date' => \App\Models\Setting::get_value('pmb_start_date', '2026-06-01 00:00:00'),
            'pmb_status_link' => \App\Models\Setting::get_value('pmb_status_link', route('pmb.status')),
        ];

        $brosurs = BrosurPmb::where('is_active', true)->orderBy('order')->get();

        return view('pmb', compact('settings', 'brosurs'));
    }
}
