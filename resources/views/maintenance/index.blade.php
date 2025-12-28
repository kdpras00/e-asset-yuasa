@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Riwayat Perbaikan</h1>
            <p class="text-gray-500 mt-1">Daftar pengajuan perbaikan aset dan status pengerjaan.</p>
        </div>
        
        <!-- Only show Create button if allowed (e.g. not just for pimpinan, or everyone can request?) -->
        <!-- Usually everyone can Request, only Pimpinan approves. -->
        @if(Auth::user()->role != 'hrd')
        <a href="{{ route('maintenance.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg flex items-center transition-transform hover:-translate-y-0.5">
            <i class="fas fa-plus mr-2"></i> Ajukan Perbaikan
        </a>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Informasi Aset</th>
                        <th class="px-6 py-4">Pelapor & Tanggal</th>
                        <th class="px-6 py-4">Kerusakan</th>
                        <th class="px-6 py-4">Biaya</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($maintenances as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            @if($item->asset)
                                <div class="font-bold text-gray-800">{{ $item->asset->name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->asset->code }}</div>
                            @else
                                <span class="text-red-400">Aset Terhapus</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-700">User (TODO)</div> {{-- We need to link User to Request later --}}
                            <div class="text-xs text-gray-400">{{ $item->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <div class="line-clamp-2 text-gray-600">{{ $item->description }}</div>
                            @if($item->image)
                                <a href="{{ Storage::url($item->image) }}" target="_blank" class="text-xs text-blue-500 hover:underline mt-1 block">
                                    <i class="fas fa-image mr-1"></i> Lihat Bukti
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            Rp {{ number_format($item->cost, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600 border border-yellow-100">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @elseif($item->status == 'approved')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Proses
                                </span>
                            @elseif($item->status == 'completed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                </span>
                            @elseif($item->status == 'rejected')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(trim(Auth::user()->role) == 'pimpinan' && $item->status == 'pending')
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('reports.maintenance.approve', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('reports.maintenance.reject', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @elseif($item->status == 'approved' && Auth::user()->role != 'pimpinan')
                                {{-- Staff can mark as completed --}}
                                <form action="{{ route('reports.maintenance.complete', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-bold" title="Tandai Selesai">
                                        Selesai
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-clipboard-check text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada data perbaikan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $maintenances->links() }}
        </div>
    </div>
</div>
@endsection
