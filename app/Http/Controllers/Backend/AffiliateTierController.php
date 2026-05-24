<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AffiliateTier;
use Illuminate\Http\Request;

class AffiliateTierController extends Controller
{
    public function index()
    {
        $tiers = AffiliateTier::orderBy('min_referrals')->get();
        return view('backend.admin.affiliates.tiers', compact('tiers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tiers' => 'required|array',
            'tiers.*.id' => 'required|exists:affiliate_tiers,id',
            'tiers.*.name' => 'required|string|max:255',
            'tiers.*.min_referrals' => 'required|integer|min:0',
            'tiers.*.commission_amount' => 'required|numeric|min:0',
        ]);

        foreach ($request->tiers as $tierData) {
            $tier = AffiliateTier::find($tierData['id']);
            if ($tier) {
                $tier->update([
                    'name' => $tierData['name'],
                    'min_referrals' => $tierData['min_referrals'],
                    'commission_amount' => $tierData['commission_amount'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pengaturan Tier Komisi berhasil diperbarui.');
    }
}
