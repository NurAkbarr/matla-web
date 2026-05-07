<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $affiliates = Affiliate::with('user')->withCount('registrations')->latest()->paginate(10);
        $users = User::whereNotIn('id', Affiliate::pluck('user_id'))->orderBy('name')->get();

        return view('backend.admin.affiliates.index', compact('affiliates', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id|unique:affiliates,user_id',
            'name' => 'nullable|string|max:255|required_without:user_id',
            'whatsapp_number' => 'nullable|string|max:20|required_without:user_id',
            'affiliate_code' => 'required|string|unique:affiliates,affiliate_code|max:20',
            'commission_rate' => 'required|numeric|min:0',
        ]);

        Affiliate::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'whatsapp_number' => $request->whatsapp_number,
            'affiliate_code' => strtoupper($request->affiliate_code),
            'commission_rate' => $request->commission_rate,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Afiliator berhasil ditambahkan.');
    }

    public function commissions()
    {
        $commissions = AffiliateCommission::with(['affiliate.user', 'registration'])
            ->latest()
            ->paginate(15);

        return view('backend.admin.affiliates.commissions', compact('commissions'));
    }

    public function markPaid(AffiliateCommission $commission)
    {
        if ($commission->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya komisi dengan status "approved" yang bisa dicairkan.');
        }

        $commission->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Komisi berhasil ditandai sebagai "Sudah Dibayar".');
    }

    public function toggleStatus(Affiliate $affiliate)
    {
        $affiliate->update([
            'is_active' => !$affiliate->is_active
        ]);

        return redirect()->back()->with('success', 'Status afiliator berhasil diperbarui.');
    }

    public function destroy(Affiliate $affiliate)
    {
        $affiliate->delete();
        return redirect()->back()->with('success', 'Afiliator berhasil dihapus.');
    }
}
