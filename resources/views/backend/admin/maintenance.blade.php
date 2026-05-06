@extends('layouts.backend')

@section('title', 'Maintenance')
@section('breadcrumb', 'Maintenance')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl md:text-2xl font-extrabold text-gray-900">Maintenance Tools</h3>
                <p class="text-sm text-gray-500 mt-1 font-semibold">
                    Menu ini hanya untuk <span class="font-black">super admin</span>. Pastikan situs sudah menggunakan HTTPS.
                </p>
            </div>
        </div>

        <div class="mt-6 space-y-6">
            <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50">
                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Jalankan Migration</h4>
                <p class="text-xs text-gray-500 mt-2 font-semibold">
                    Menjalankan <code class="px-1 py-0.5 bg-white rounded border">php artisan migrate --force</code>.
                </p>

                <form method="POST" action="{{ route('maintenance.migrate') }}" class="mt-4 flex flex-col sm:flex-row gap-3 sm:items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Maintenance Token</label>
                        <input
                            type="password"
                            name="token"
                            autocomplete="off"
                            required
                            placeholder="Isi MAINTENANCE_TOKEN"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                        />
                    </div>
                    <button
                        type="submit"
                        onclick="return confirm('Jalankan migrate sekarang?')"
                        class="px-5 py-3 rounded-xl bg-primary text-white font-black uppercase tracking-widest text-[10px] hover:bg-primary-dark transition-all shadow-lg shadow-primary/15"
                    >
                        Migrate
                    </button>
                </form>
            </div>

            <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50">
                <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Clear Cache</h4>
                <p class="text-xs text-gray-500 mt-2 font-semibold">
                    Menjalankan <code class="px-1 py-0.5 bg-white rounded border">php artisan optimize:clear</code>.
                </p>

                <form method="POST" action="{{ route('maintenance.clear_cache') }}" class="mt-4 flex flex-col sm:flex-row gap-3 sm:items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Maintenance Token</label>
                        <input
                            type="password"
                            name="token"
                            autocomplete="off"
                            required
                            placeholder="Isi MAINTENANCE_TOKEN"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                        />
                    </div>
                    <button
                        type="submit"
                        onclick="return confirm('Bersihkan cache sekarang?')"
                        class="px-5 py-3 rounded-xl bg-gray-900 text-white font-black uppercase tracking-widest text-[10px] hover:bg-black transition-all shadow-lg shadow-gray-900/15"
                    >
                        Clear Cache
                    </button>
                </form>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <h4 class="text-sm font-black text-amber-900 uppercase tracking-widest">Catatan Keamanan</h4>
                <ul class="mt-3 text-xs text-amber-900/80 font-semibold space-y-2 list-disc pl-5">
                    <li>Jangan bagikan token. Simpan hanya di <code class="px-1 py-0.5 bg-white rounded border">.env</code> production.</li>
                    <li>Jika token bocor, segera ganti nilainya.</li>
                    <li>Endpoint maintenance sudah di-throttle; jangan spam klik.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

