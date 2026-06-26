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
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-sm transition-all">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl border border-blue-100">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 line-clamp-1">{{ $cat->category }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Updated {{ \Carbon\Carbon::parse($cat->last_update)->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Items</span>
                    <span class="text-sm font-bold text-gray-900">{{ $cat->count }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Valuation</span>
                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($cat->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('assets.index', ['category' => $cat->category]) }}" class="w-full py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-bold rounded-xl text-center transition-colors">
                View Assets
            </a>
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
