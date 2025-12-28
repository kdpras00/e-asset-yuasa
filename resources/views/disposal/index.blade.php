@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Riwayat Pemusnahan</h1>
            <p class="text-gray-500 mt-1">Daftar pengajuan pemusnahan aset dan status approval.</p>
        </div>
        
        @if(Auth::user()->role != 'hrd')
        <a href="{{ route('disposal.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg flex items-center transition-transform hover:-translate-y-0.5">
            <i class="fas fa-trash-alt mr-2"></i> Ajukan Pemusnahan
        </a>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Aset</th>
                        <th class="px-6 py-4">Tanggal Pengajuan</th>
                        <th class="px-6 py-4">Alasan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($disposals as $item)
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
                            <div class="font-medium text-gray-700">{{ $item->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <div class="line-clamp-2 text-gray-600">{{ $item->reason }}</div>
                            @if($item->notes && $item->notes != 'Bulk input from Laporan')
                                <div class="text-xs text-gray-400 mt-1">Note: {{ $item->notes }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$item->approved_by)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600 border border-yellow-100">
                                    <i class="fas fa-clock mr-1"></i> Menunggu Approval
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                    <i class="fas fa-check-circle mr-1"></i> Disetujui
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(trim(Auth::user()->role) == 'pimpinan' && !$item->approved_by)
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('reports.disposal.approve', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 text-xs font-bold flex items-center">
                                            <i class="fas fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('reports.disposal.reject', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold flex items-center">
                                            <i class="fas fa-times mr-1"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            @elseif($item->approved_by)
                                <span class="text-xs text-gray-400">Approved by {{ $item->approver->name ?? 'Pimpinan' }}</span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-trash-restore text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada data pemusnahan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $disposals->links() }}
        </div>
    </div>
</div>
@endsection
