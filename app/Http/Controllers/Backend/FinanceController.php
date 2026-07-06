<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Show the secret login form.
     */
    public function login()
    {
        // If already unlocked, redirect to dashboard
        if (session('finance_unlocked')) {
            return redirect()->route('backend.admin.finance.dashboard');
        }

        return view('backend.admin.finance.login');
    }

    /**
     * Handle PIN authentication.
     */
    public function auth(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        // Secret PIN for unlocking (defaults to 123456 if not set in .env)
        $secretPin = env('FINANCE_PIN', '123456');

        if ($request->pin === $secretPin) {
            session(['finance_unlocked' => true]);
            return redirect()->route('backend.admin.finance.dashboard');
        }

        return back()->with('error', 'PIN tidak valid!');
    }

    /**
     * Show the finance dashboard.
     */
    public function dashboard()
    {
        if (!session('finance_unlocked')) {
            return redirect()->route('backend.admin.finance.login');
        }

        $currentMonth = \Carbon\Carbon::now()->format('Y-m');
        $totalIncome = \App\Models\FinanceTransaction::where('type', 'income')->sum('amount');
        $totalExpense = \App\Models\FinanceTransaction::where('type', 'expense')->sum('amount');
        
        $monthIncome = \App\Models\FinanceTransaction::where('type', 'income')
            ->where('transaction_date', 'like', $currentMonth . '%')->sum('amount');
        $monthExpense = \App\Models\FinanceTransaction::where('type', 'expense')
            ->where('transaction_date', 'like', $currentMonth . '%')->sum('amount');
            
        // Active balance is now sum of all wallets
        $activeBalance = \App\Models\FinanceWallet::sum('balance');

        // Fetch data
        $transactions = \App\Models\FinanceTransaction::with(['category', 'wallet'])->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->limit(10)->get();
        $categories = \App\Models\FinanceCategory::orderBy('type')->orderBy('name')->get();
        $wallets = \App\Models\FinanceWallet::orderBy('type')->orderBy('name')->get();

        return view('backend.admin.finance.dashboard', compact(
            'activeBalance', 'monthIncome', 'monthExpense', 'transactions', 'categories', 'wallets'
        ));
    }

    /**
     * Store new transaction.
     */
    public function store(Request $request)
    {
        if (!session('finance_unlocked')) {
            return redirect()->route('backend.admin.finance.login');
        }

        $request->validate([
            'finance_category_id' => 'required|exists:finance_categories,id',
            'finance_wallet_id' => 'required|exists:finance_wallets,id',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $category = \App\Models\FinanceCategory::find($request->finance_category_id);
        $wallet = \App\Models\FinanceWallet::find($request->finance_wallet_id);

        \App\Models\FinanceTransaction::create([
            'finance_category_id' => $category->id,
            'finance_wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'type' => $category->type,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        // Update Wallet Balance
        if ($category->type === 'income') {
            $wallet->increment('balance', $request->amount);
        } else {
            $wallet->decrement('balance', $request->amount);
        }

        return redirect()->route('backend.admin.finance.dashboard')->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Show the categories management page.
     */
    public function categories()
    {
        if (!session('finance_unlocked')) {
            return redirect()->route('backend.admin.finance.login');
        }

        $categories = \App\Models\FinanceCategory::withCount('transactions')->orderBy('type')->orderBy('name')->get();
        return view('backend.admin.finance.categories', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request)
    {
        if (!session('finance_unlocked')) {
            return redirect()->route('backend.admin.finance.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:10',
        ]);

        \App\Models\FinanceCategory::create([
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $request->icon ?? '🏷️',
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Delete a category.
     */
    public function destroyCategory($id)
    {
        if (!session('finance_unlocked')) {
            return redirect()->route('backend.admin.finance.login');
        }

        $category = \App\Models\FinanceCategory::findOrFail($id);
        
        // Prevent deletion if it has transactions
        if ($category->transactions()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena sudah memiliki histori transaksi.');
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Show the wallets management page.
     */
    public function wallets()
    {
        if (!session('finance_unlocked')) {
            return redirect()->route('backend.admin.finance.login');
        }

        $wallets = \App\Models\FinanceWallet::withCount('transactions')->orderBy('type')->orderBy('name')->get();
        return view('backend.admin.finance.wallets', compact('wallets'));
    }

    /**
     * Store a new wallet.
     */
    public function storeWallet(Request $request)
    {
        if (!session('finance_unlocked')) {
            return redirect()->route('backend.admin.finance.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,ewallet,credit',
            'balance' => 'required|numeric',
            'icon' => 'nullable|string|max:10',
        ]);

        \App\Models\FinanceWallet::create([
            'name' => $request->name,
            'type' => $request->type,
            'balance' => $request->balance,
            'icon' => $request->icon ?? '💳',
        ]);

        return back()->with('success', 'Dompet/Akun baru berhasil ditambahkan.');
    }

    /**
     * Delete a wallet.
     */
    public function destroyWallet($id)
    {
        if (!session('finance_unlocked')) {
            return redirect()->route('backend.admin.finance.login');
        }

        $wallet = \App\Models\FinanceWallet::findOrFail($id);
        
        // Prevent deletion if it has transactions
        if ($wallet->transactions()->count() > 0) {
            return back()->with('error', 'Dompet tidak dapat dihapus karena sudah memiliki histori transaksi.');
        }

        $wallet->delete();
        return back()->with('success', 'Dompet berhasil dihapus.');
    }

    /**
     * Lock the finance module.
     */
    public function logout()
    {
        session()->forget('finance_unlocked');
        return redirect()->route('backend.admin.finance.login')->with('success', 'Modul Keuangan Pribadi telah dikunci.');
    }
}
