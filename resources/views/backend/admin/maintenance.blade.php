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

                <form method="POST" action="{{ route('maintenance.migrate') }}" id="form-migrate" class="mt-4 flex flex-col sm:flex-row gap-3 sm:items-end">
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
                        type="button"
                        onclick="handleMaintenance('form-migrate', 'Jalankan migration sekarang?')"
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

                <form method="POST" action="{{ route('maintenance.clear_cache') }}" id="form-cache" class="mt-4 flex flex-col sm:flex-row gap-3 sm:items-end">
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
                        type="button"
                        onclick="handleMaintenance('form-cache', 'Bersihkan cache sekarang?')"
                        class="px-5 py-3 rounded-xl bg-gray-900 text-white font-black uppercase tracking-widest text-[10px] hover:bg-black transition-all shadow-lg shadow-gray-900/15"
                    >
                        Clear Cache
                    </button>
                </form>
            </div>

            <div class="p-5 rounded-2xl border border-red-100 bg-red-50">
                <h4 class="text-sm font-black text-red-900 uppercase tracking-widest">Reset Password Mahasiswa</h4>
                <p class="text-xs text-red-500 mt-2 font-semibold">
                    Semua akun role <span class="font-black">mahasiswa</span> akan di-reset passwordnya menjadi <code class="px-1 py-0.5 bg-white rounded border border-red-200 font-black">password123</code>.
                </p>

                <form method="POST" action="{{ route('maintenance.reset_password_mhs') }}" id="form-reset-password" class="mt-4 flex flex-col sm:flex-row gap-3 sm:items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-red-500 uppercase tracking-widest mb-2">Maintenance Token</label>
                        <input
                            type="password"
                            name="token"
                            autocomplete="off"
                            required
                            placeholder="Isi MAINTENANCE_TOKEN"
                            class="w-full px-4 py-3 rounded-xl border border-red-200 bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500"
                        />
                    </div>
                    <button
                        type="button"
                        onclick="handleMaintenance('form-reset-password', 'HATI-HATI! Anda akan me-reset SEMUA password mahasiswa menjadi password123. Lanjutkan?')"
                        class="px-5 py-3 rounded-xl bg-red-600 text-white font-black uppercase tracking-widest text-[10px] hover:bg-red-700 transition-all shadow-lg shadow-red-600/15"
                    >
                        Reset Massal
                    </button>
                </form>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <h4 class="text-sm font-black text-amber-900 uppercase tracking-widest">Catatan Keamanan</h4>
                <ul class="mt-3 text-xs text-amber-900/80 font-semibold space-y-2 list-disc pl-5">
                    <li>Jangan bagikan token. Simpan hanya di <code class="px-1 py-0.5 bg-white rounded border">.env</code> production.</li>
                    <li>Jika token bocor, segera ganti nilainya.</li>
                    <li>Endpoint maintenance sudah dilonggarkan; namun tetap hati-hati.</li>
                </ul>
            </div>
        </div>
    </div>
</div>


    <script>
        console.log('Maintenance Script Loaded');
        function handleMaintenance(formId, message) {
            console.log('Button clicked for form:', formId);
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 not loaded! Falling back to confirm.');
                if (confirm(message)) {
                    document.getElementById(formId).submit();
                }
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Maintenance',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#374151',
                confirmButtonText: 'Ya, Jalankan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-[2rem]'
                        }
                    });
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endsection

