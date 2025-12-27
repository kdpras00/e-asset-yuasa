@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Ringkasan Laporan</h1>
        <p class="text-gray-500 mt-1">Gambaran umum distribusi aset dan aktivitas.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('reports.export', ['format' => 'pdf']) }}" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm">
            <i class="fas fa-file-pdf"></i>
            <span class="font-medium text-sm">Export PDF</span>
        </a>
        <a href="{{ route('reports.export', ['format' => 'excel']) }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
            <i class="fas fa-file-excel"></i>
            <span class="font-medium text-sm">Export Excel</span>
        </a>
    </div>
</div>

<!-- Stats Breakdown -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Asset -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Aset</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalAssets) }}</p>
        </div>
        <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
            <i class="fas fa-boxes"></i>
        </div>
    </div>

    <!-- Total Value -->
    <!-- <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Asset Value</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
        </div>
        <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
            <i class="fas fa-money-bill-wave"></i>
        </div>
    </div> -->

    <!-- Active -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aktif</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($activeAssets) }}</p>
        </div>
        <div class="h-10 w-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <!-- Maintenance -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perbaikan</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($maintenanceAssets) }}</p>
        </div>
        <div class="h-10 w-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600">
            <i class="fas fa-tools"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Category Chart -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Aset berdasarkan Kategori</h3>
        
        <!-- Simple Bar Representation since we don't have a Chart lib installed yet -->
        <div class="space-y-4">
            @foreach($assetsByCategory as $item)
                @php
                    $percentage = $totalAssets > 0 ? ($item->total / $totalAssets) * 100 : 0;
                    $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-yellow-500', 'bg-red-500', 'bg-indigo-500'];
                    $color = $colors[$loop->index % count($colors)];
                @endphp
                <div>
                    <div class="flex justify-between items-end mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $item->category }}</span>
                        <span class="text-xs text-gray-500 font-bold">{{ $item->total }} ({{ round($percentage, 1) }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Documents -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Dokumen Terbaru</h3>
        <div class="flow-root">
            <ul role="list" class="-mb-8">
                @forelse($recentDocuments as $doc)
                <li>
                    <div class="relative pb-8">
                        @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $doc->status == 'approved' ? 'bg-green-500' : ($doc->status == 'rejected' ? 'bg-red-500' : 'bg-gray-400') }}">
                                    <i class="fas fa-file-alt text-white text-xs"></i>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-500"><span class="font-medium text-gray-900">{{ $doc->asset->name }}</span> - {{ $doc->type }}</p>
                                </div>
                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                    <time datetime="{{ $doc->created_at }}">{{ $doc->created_at->diffForHumans() }}</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                  <p class="text-sm text-gray-500 italic">Tidak ada dokumen terbaru.</p>
                @endforelse
            </ul>
        </div>
        <div class="mt-6 border-t border-gray-100 pt-4">
            <a href="{{ route('reports.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">Lihat semua dokumen <span aria-hidden="true">&rarr;</span></a>
        </div>
    </div>
</div>
@endsection
