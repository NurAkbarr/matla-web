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
        ]);

        Affiliate::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'whatsapp_number' => $request->whatsapp_number,
            'affiliate_code' => strtoupper($request->affiliate_code),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Afiliator berhasil ditambahkan.');
    }

    public function commissions()
    {
        $affiliates = Affiliate::with(['user', 'registrations', 'commissions'])
            ->withCount('registrations')
            ->having('registrations_count', '>', 0)
            ->paginate(15);

        // Calculate dynamic tiers
        $tiers = \App\Models\AffiliateTier::orderBy('min_referrals', 'desc')->get();

        foreach ($affiliates as $aff) {
            $totalReferrals = $aff->registrations_count;
            $currentTier = $tiers->firstWhere('min_referrals', '<=', $totalReferrals);
            
            $aff->tier_name = $currentTier ? $currentTier->name : 'Tidak Ada Tier';
            $aff->tier_rate = $currentTier ? $currentTier->commission_amount : 0;
            $aff->total_earned = $totalReferrals * $aff->tier_rate;
            $aff->total_paid = $aff->commissions()->where('status', 'paid')->sum('amount');
            $aff->unpaid_balance = $aff->total_earned - $aff->total_paid;
        }

        return view('backend.admin.affiliates.commissions', compact('affiliates'));
    }

    public function payBalance(Affiliate $affiliate)
    {
        $totalReferrals = $affiliate->registrations()->count();
        $tiers = \App\Models\AffiliateTier::orderBy('min_referrals', 'desc')->get();
        $currentTier = $tiers->firstWhere('min_referrals', '<=', $totalReferrals);
        
        $tierRate = $currentTier ? $currentTier->commission_amount : 0;
        $totalEarned = $totalReferrals * $tierRate;
        $totalPaid = $affiliate->commissions()->where('status', 'paid')->sum('amount');
        $unpaidBalance = $totalEarned - $totalPaid;

        if ($unpaidBalance <= 0) {
            return redirect()->back()->with('error', 'Tidak ada sisa komisi yang perlu dibayarkan.');
        }

        // Record the payment
        AffiliateCommission::create([
            'affiliate_id' => $affiliate->id,
            'pmb_registration_id' => null, // Nullable now, represents a bulk payment
            'amount' => $unpaidBalance,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Sisa komisi sebesar Rp ' . number_format($unpaidBalance, 0, ',', '.') . ' berhasil dibayarkan.');
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
