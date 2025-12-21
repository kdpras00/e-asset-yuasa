@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header & Summary -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Kategori Asset</h1>
            <p class="text-gray-500 mt-1">Klasifikasi dan statistik aset perusahaan.</p>
        </div>
        
        <!-- Quick Stats using Collection methods -->
        <div class="flex gap-4">
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="fas fa-cubes"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Aset</p>
                    <p class="text-lg font-bold text-gray-800">{{ $categories->sum('count') }}</p>
                </div>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hidden sm:flex">
                <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Estimasi Nilai</p>
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($categories->sum('total_price'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $cat)
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group relative overflow-hidden">
            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700 ease-in-out"></div>
            
            <div class="relative z-10">
                <!-- Card Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#0A1A32] to-[#2a456b] flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-blue-900/20 group-hover:scale-110 transition-transform duration-300">
                            {{ substr($cat->category, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 group-hover:text-[#0A1A32] transition-colors line-clamp-1">{{ $cat->category }}</h3>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                <i class="far fa-clock text-[10px]"></i>
                                {{ \Carbon\Carbon::parse($cat->last_update)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Stats Sections -->
                <div class="space-y-4">
                    <!-- Asset Count Progress -->
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-sm text-gray-500 font-medium">Total Item</span>
                            <span class="text-lg font-bold text-gray-800">{{ $cat->count }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ min($cat->count * 2, 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Valuation -->
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 group-hover:bg-blue-50/30 group-hover:border-blue-100 transition-colors">
                         <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Valuasi</p>
                         <p class="text-base font-bold text-gray-800 font-mono">
                             Rp {{ number_format($cat->total_price, 0, ',', '.') }}
                         </p>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between">
                     <a href="{{ route('assets.index', ['category' => $cat->category]) }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                         Lihat Detail <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                     </a>
                     <div class="flex -space-x-2">
                         <!-- Placeholder avatars for "users" related to this category if we had that data, keeping it visual only for now with generic icons or remove -->
                     </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
            <div class="bg-gray-100 rounded-full p-6 mb-4">
                <i class="fas fa-folder-open text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Belum ada Kategori</h3>
            <p class="text-gray-500 max-w-sm mx-auto mt-2">Belum ada data kategori aset yang ditemukan dalam sistem.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
