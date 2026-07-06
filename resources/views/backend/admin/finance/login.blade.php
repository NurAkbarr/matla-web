<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Access | Matla</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #022c22; }
        
        /* Custom Numpad Button */
        .numpad-btn {
            background: white;
            color: #334155;
            font-size: 1.25rem;
            font-weight: 700;
            border-radius: 50%;
            width: 3.5rem;
            height: 3.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            margin: 0 auto;
            cursor: pointer;
        }
        .numpad-btn:hover, .numpad-btn:active {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: scale(1.05);
        }

        .bg-wave-left {
            background-color: #064e3b;
            background-image: url('{{ asset("assets/bg-web.png") }}');
            background-size: cover;
            background-position: center bottom;
            background-blend-mode: overlay;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 selection:bg-emerald-500 selection:text-white relative overflow-hidden">
    
    <!-- Big background abstract wave behind the whole card -->
    <div class="absolute inset-0 bg-emerald-950 pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[70%] bg-emerald-800/40 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[10%] right-[5%] w-[50%] h-[50%] bg-emerald-900/60 rounded-full blur-[100px]"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-[750px] h-[500px] max-h-[90vh] bg-white rounded-[2rem] shadow-2xl relative overflow-hidden flex flex-col md:flex-row z-10 border border-emerald-800/20">
        
        <!-- Left Side: Branding -->
        <div class="md:w-5/12 bg-wave-left relative overflow-hidden p-8 flex flex-col justify-center border-r border-emerald-900/20">
            <!-- decorative overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-950/90 via-emerald-900/80 to-emerald-900/70 z-0"></div>
            
            <!-- Abstract curve SVG -->
            <svg class="absolute inset-y-0 right-0 h-full w-auto text-emerald-800/30 -mr-16 pointer-events-none z-0" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0,0 C50,30 80,70 100,100 L100,0 Z" fill="currentColor"/>
            </svg>

            <div class="relative z-10">
                <!-- Logo -->
                <div class="flex items-center space-x-3 mb-10">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg">
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                    </div>
                    <span class="text-white font-black tracking-widest text-2xl drop-shadow-md">MATLA</span>
                </div>
                
                <!-- Text -->
                <div>
                    <h2 class="text-emerald-100 text-base font-medium mb-1">Selamat datang di</h2>
                    <h1 class="text-white text-3xl font-black tracking-tight mb-3 drop-shadow-lg">My Finance</h1>
                    <p class="text-emerald-200/90 text-xs leading-relaxed max-w-[95%] font-medium">
                        Kelola keuangan kampus dengan mudah, aman, dan terintegrasi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side: PIN Form -->
        <div class="md:w-7/12 bg-white p-6 flex flex-col justify-center items-center relative">
            
            <div class="w-full max-w-xs flex flex-col items-center">
                
                <!-- Lock Icon -->
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-3 border border-emerald-100 shadow-inner">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <h2 class="text-slate-800 text-xl font-black tracking-tight mb-0.5">Super Admin</h2>
                <p class="text-slate-500 text-xs font-medium mb-5">Masukkan PIN Anda untuk melanjutkan</p>

                @if(session('error'))
                    <div class="mb-4 w-full text-center bg-rose-50 text-rose-600 px-4 py-2 rounded-xl border border-rose-100 text-xs font-bold shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- PIN Indicator (6 dots) inside a pill -->
                <div id="pinDots" class="bg-slate-50/50 border border-slate-100 rounded-full px-6 py-2.5 flex space-x-3 shadow-inner mb-6 w-full justify-center">
                    <div class="dot w-3 h-3 rounded-full transition-all duration-300 bg-slate-300"></div>
                    <div class="dot w-3 h-3 rounded-full transition-all duration-300 bg-slate-300"></div>
                    <div class="dot w-3 h-3 rounded-full transition-all duration-300 bg-slate-300"></div>
                    <div class="dot w-3 h-3 rounded-full transition-all duration-300 bg-slate-300"></div>
                    <div class="dot w-3 h-3 rounded-full transition-all duration-300 bg-slate-300"></div>
                    <div class="dot w-3 h-3 rounded-full transition-all duration-300 bg-slate-300"></div>
                </div>

                <!-- Hidden Form -->
                <form id="pinForm" action="{{ route('backend.admin.finance.auth') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="pin" id="pinInput" value="">
                </form>

                <!-- Numpad -->
                <div class="w-full px-2 mb-4">
                    <div class="grid grid-cols-3 gap-y-3 gap-x-3 text-center">
                        <button type="button" onclick="addDigit(1)" class="numpad-btn select-none">1</button>
                        <button type="button" onclick="addDigit(2)" class="numpad-btn select-none">2</button>
                        <button type="button" onclick="addDigit(3)" class="numpad-btn select-none">3</button>
                        <button type="button" onclick="addDigit(4)" class="numpad-btn select-none">4</button>
                        <button type="button" onclick="addDigit(5)" class="numpad-btn select-none">5</button>
                        <button type="button" onclick="addDigit(6)" class="numpad-btn select-none">6</button>
                        <button type="button" onclick="addDigit(7)" class="numpad-btn select-none">7</button>
                        <button type="button" onclick="addDigit(8)" class="numpad-btn select-none">8</button>
                        <button type="button" onclick="addDigit(9)" class="numpad-btn select-none">9</button>
                        
                        <div></div>
                        
                        <button type="button" onclick="addDigit(0)" class="numpad-btn select-none">0</button>
                        
                        <!-- Backspace button styled a bit differently -->
                        <button type="button" onclick="removeDigit()" class="numpad-btn !bg-emerald-50 !border-emerald-100 !text-emerald-700 hover:!bg-emerald-100 select-none">
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Footer text -->
                <div class="flex items-center space-x-1.5 text-slate-400 text-[10px] font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Akses aman & terenkripsi</span>
                </div>

                <a href="{{ url('/') }}" class="mt-4 text-emerald-600/70 hover:text-emerald-700 text-xs font-bold tracking-wider uppercase transition-colors">
                    Kembali ke Beranda
                </a>

            </div>
        </div>
    </div>

    <script>
        let currentPin = '';
        const dots = document.querySelectorAll('.dot');
        const pinInput = document.getElementById('pinInput');
        const pinForm = document.getElementById('pinForm');

        function updateDots() {
            dots.forEach((dot, index) => {
                if (index < currentPin.length) {
                    dot.classList.remove('bg-slate-300');
                    dot.classList.add('bg-emerald-500', 'shadow-[0_0_8px_rgba(16,185,129,0.5)]', 'scale-110');
                } else {
                    dot.classList.add('bg-slate-300');
                    dot.classList.remove('bg-emerald-500', 'shadow-[0_0_8px_rgba(16,185,129,0.5)]', 'scale-110');
                }
            });
        }

        function addDigit(digit) {
            if (currentPin.length < 6) {
                currentPin += digit.toString();
                pinInput.value = currentPin;
                updateDots();
                
                if (currentPin.length === 6) {
                    setTimeout(() => {
                        pinForm.submit();
                    }, 300);
                }
            }
        }

        function removeDigit() {
            if (currentPin.length > 0) {
                currentPin = currentPin.slice(0, -1);
                pinInput.value = currentPin;
                updateDots();
            }
        }
    </script>
</body>
</html>
