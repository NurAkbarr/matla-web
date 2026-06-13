@extends('layouts.app')

@section('title', 'Struktur Organisasi - MATLA')

@section('content')
<div class="pt-32 pb-20 relative overflow-hidden" style="background-image: url('{{ asset('assets/bg-aksen.png') }}'); background-size: cover; background-position: center;">
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Struktur Organisasi</h1>
        <p class="text-emerald-50/80 text-lg max-w-2xl mx-auto font-medium">Mengenal lebih dekat jajaran pengurus dan struktural Kampus MATLA yang berdedikasi.</p>
    </div>
</div>

<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        
        <!-- Struktur Yayasan -->
        <div class="mb-24">
            <div class="text-center mb-10">
                <span class="text-emerald-600 font-bold tracking-widest uppercase text-sm">Yayasan MATLA</span>
                <h2 class="text-3xl font-black text-gray-900 mt-2">Struktur Yayasan</h2>
            </div>
            
            <!-- Yayasan Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 max-w-5xl mx-auto">
                <!-- Pembina -->
                <div class="col-span-full flex justify-center mb-2 md:mb-4">
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 text-center shadow-sm w-full max-w-sm hover:-translate-y-1 transition-transform">
                        <div class="text-gray-900 font-black text-lg mb-1">Pembina</div>
                        <div class="text-gray-800 font-medium">Heru Fantono</div>
                    </div>
                </div>
                <!-- Ketua -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center shadow-sm hover:border-emerald-500 hover:shadow-md transition-all">
                    <div class="text-gray-900 font-black text-lg mb-1">Ketua</div>
                    <div class="text-gray-800 font-medium">Taufik Akbar</div>
                </div>
                <!-- Sekretaris -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center shadow-sm hover:border-emerald-500 hover:shadow-md transition-all">
                    <div class="text-gray-900 font-black text-lg mb-1">Sekretaris</div>
                    <div class="text-gray-800 font-medium">Habil Habani</div>
                </div>
                <!-- Bendahara -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center shadow-sm hover:border-emerald-500 hover:shadow-md transition-all">
                    <div class="text-gray-900 font-black text-lg mb-1">Bendahara</div>
                    <div class="text-gray-800 font-medium">Ainil Ahadi</div>
                </div>
                <!-- Pengawas -->
                <div class="col-span-full flex justify-center mt-2 md:mt-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-center shadow-sm w-full max-w-sm hover:border-emerald-500 hover:shadow-md transition-all">
                        <div class="text-gray-900 font-black text-lg mb-1">Pengawas</div>
                        <div class="text-gray-800 font-medium">Muhammad Fikri Arrasyid</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Struktur Kampus -->
        <div>
            <div class="text-center mb-10 md:mb-16">
                <span class="text-emerald-600 font-bold tracking-widest uppercase text-sm">Akademik & Operasional</span>
                <h2 class="text-3xl font-black text-gray-900 mt-2">Struktur Kampus Matla</h2>
            </div>

            <!-- Mobile Layout (< 1024px) -->
            <div class="lg:hidden flex flex-col items-center px-4 w-full font-sans">
                <!-- Mudir -->
                <div class="bg-emerald-50 border-2 border-emerald-500 rounded-2xl p-5 text-center shadow-sm w-full max-w-sm">
                    <div class="text-gray-900 font-black text-lg md:text-xl mb-1">Mudir (Direktur)</div>
                    <div class="text-gray-800 font-medium text-sm md:text-base">Ustadz Zainudin Arfi, S.Pd</div>
                </div>
                
                <div class="w-[3px] h-8 bg-gray-800"></div>

                <!-- Wakil Mudir -->
                <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 text-center shadow-sm w-full max-w-sm">
                    <div class="text-gray-900 font-bold text-base md:text-lg mb-1">Wakil Mudir</div>
                    <div class="text-gray-800 font-medium text-sm">Heru Fantono</div>
                </div>

                <div class="w-[3px] h-8 bg-gray-800"></div>

                <!-- Bag Akademik -->
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-center shadow-sm w-full max-w-sm">
                    <div class="text-gray-900 font-bold text-base md:text-lg mb-1">Bag. Akademik</div>
                    <div class="text-gray-800 font-medium text-sm italic">— (kosong)</div>
                </div>

                <div class="w-[3px] h-8 bg-gray-800"></div>

                <!-- Admin -->
                <div class="bg-purple-50 border border-purple-200 rounded-2xl p-4 text-center shadow-sm w-full max-w-sm">
                    <div class="text-gray-900 font-bold text-base md:text-lg mb-1">Admin</div>
                    <div class="text-gray-800 font-medium text-sm">Ainil Ahadi</div>
                </div>

                <div class="w-[3px] h-8 bg-gray-800"></div>

                <!-- Admin Prodi -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-center shadow-sm w-full max-w-sm">
                    <div class="text-gray-900 font-bold text-base md:text-lg mb-1">Admin Prodi</div>
                    <div class="text-gray-800 font-medium text-sm">Asiah Qonita</div>
                </div>

                <!-- Tim Digital Divider -->
                <div class="mt-12 w-full max-w-sm">
                    <div class="flex items-center justify-center mb-6">
                        <div class="h-[2px] bg-gray-800 w-full"></div>
                        <div class="px-4 text-gray-900 font-black text-xs uppercase tracking-[0.2em] whitespace-nowrap">Tim Digital</div>
                        <div class="h-[2px] bg-gray-800 w-full"></div>
                    </div>
                    
                    <!-- Tim Digital Stack -->
                    <div class="flex flex-col gap-4">
                        <div class="bg-orange-50 border border-orange-200 rounded-2xl px-6 py-4 text-center shadow-sm w-full">
                            <div class="text-gray-900 font-bold text-sm md:text-base mb-1">Admin Medsos</div>
                            <div class="text-gray-800 font-medium text-sm">Amilia</div>
                        </div>
                        <div class="bg-rose-50 border border-rose-200 rounded-2xl px-6 py-4 text-center shadow-sm w-full">
                            <div class="text-gray-900 font-bold text-sm md:text-base mb-1">Video Editor</div>
                            <div class="text-gray-800 font-medium text-sm">Zahro</div>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl px-6 py-4 text-center shadow-sm w-full">
                            <div class="text-gray-900 font-bold text-sm md:text-base mb-1">IT</div>
                            <div class="text-gray-800 font-medium text-sm">Akbar</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Layout (>= 1024px) -->
            <div class="hidden lg:block overflow-x-auto pb-10">
                <div class="min-w-[800px] flex flex-col items-center font-sans">
                    
                    <!-- Root: Mudir -->
                    <div class="bg-emerald-50 border-2 border-emerald-500 rounded-2xl px-10 py-5 text-center shadow-sm w-[350px] relative z-10">
                        <div class="text-gray-900 font-black text-xl mb-1">Mudir (Direktur)</div>
                        <div class="text-gray-800 font-medium text-base">Ustadz Zainudin Arfi, S.Pd</div>
                    </div>
                    
                    <!-- Line down from Root -->
                    <div class="w-[3px] h-10 bg-gray-800"></div>

                    <!-- Level 2 Branching -->
                    <div class="flex items-start justify-center">
                        <!-- Branch 1: Wakil Mudir -->
                        <div class="flex flex-col items-center relative">
                            <!-- Top Right Connector Line -->
                            <div class="absolute top-0 right-0 w-1/2 h-[3px] bg-gray-800"></div>
                            <div class="w-[3px] h-8 bg-gray-800"></div>
                            
                            <div class="mx-12 bg-indigo-50 border border-indigo-200 rounded-2xl px-8 py-4 text-center shadow-sm w-[300px] relative z-10">
                                <div class="text-gray-900 font-bold text-lg mb-1">Wakil Mudir</div>
                                <div class="text-gray-800 font-medium text-sm">Heru Fantono</div>
                            </div>
                            
                            <!-- Line down from Wakil -->
                            <div class="w-[3px] h-10 bg-gray-800"></div>
                            
                            <!-- Child: Bag Akademik -->
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl px-8 py-4 text-center shadow-sm w-[300px] relative z-10">
                                <div class="text-gray-900 font-bold text-lg mb-1">Bag. Akademik</div>
                                <div class="text-gray-800 font-medium text-sm italic">— (kosong)</div>
                            </div>
                        </div>

                        <!-- Branch 2: Admin -->
                        <div class="flex flex-col items-center relative">
                            <!-- Top Left Connector Line -->
                            <div class="absolute top-0 left-0 w-1/2 h-[3px] bg-gray-800"></div>
                            <div class="w-[3px] h-8 bg-gray-800"></div>
                            
                            <div class="mx-12 bg-purple-50 border border-purple-200 rounded-2xl px-8 py-4 text-center shadow-sm w-[300px] relative z-10">
                                <div class="text-gray-900 font-bold text-lg mb-1">Admin</div>
                                <div class="text-gray-800 font-medium text-sm">Ainil Ahadi</div>
                            </div>
                            
                            <!-- Line down from Admin -->
                            <div class="w-[3px] h-10 bg-gray-800"></div>
                            
                            <!-- Child: Admin Prodi -->
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl px-8 py-4 text-center shadow-sm w-[300px] relative z-10">
                                <div class="text-gray-900 font-bold text-lg mb-1">Admin Prodi</div>
                                <div class="text-gray-800 font-medium text-sm">Asiah Qonita</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tim Digital Divider -->
                    <div class="mt-20 w-[800px]">
                        <div class="flex items-center justify-center mb-10">
                            <div class="h-[3px] bg-gray-800 w-full"></div>
                            <div class="px-6 text-gray-900 font-black text-sm uppercase tracking-[0.2em] whitespace-nowrap">Tim Digital</div>
                            <div class="h-[3px] bg-gray-800 w-full"></div>
                        </div>
                        
                        <!-- Tim Digital Flex Cards -->
                        <div class="flex justify-center gap-6">
                            <!-- Admin Medsos -->
                            <div class="bg-orange-50 border border-orange-200 rounded-2xl px-6 py-5 text-center shadow-sm flex-1 hover:-translate-y-1 transition-transform">
                                <div class="text-gray-900 font-bold text-base mb-1">Admin Medsos</div>
                                <div class="text-gray-800 font-medium text-sm">Amilia</div>
                            </div>
                            
                            <!-- Video Editor -->
                            <div class="bg-rose-50 border border-rose-200 rounded-2xl px-6 py-5 text-center shadow-sm flex-1 hover:-translate-y-1 transition-transform">
                                <div class="text-gray-900 font-bold text-base mb-1">Video Editor</div>
                                <div class="text-gray-800 font-medium text-sm">Zahro</div>
                            </div>
                            
                            <!-- IT -->
                            <div class="bg-amber-50 border border-amber-200 rounded-2xl px-6 py-5 text-center shadow-sm flex-1 hover:-translate-y-1 transition-transform">
                                <div class="text-gray-900 font-bold text-base mb-1">IT</div>
                                <div class="text-gray-800 font-medium text-sm">Akbar</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
