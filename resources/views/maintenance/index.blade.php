@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Laporan Perbaikan Aset</h1>
        <p class="text-gray-500 mt-1">Daftar riwayat perbaikan aset.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('maintenance.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            <i class="fas fa-plus"></i>
            <span class="font-medium text-sm">Tambah Data</span>
        </a>
        <a href="{{ route('reports.export', ['format' => 'excel', 'type' => 'maintenance']) }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
             <i class="fas fa-file-excel"></i>
             <span class="font-medium text-sm">Export Excel</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-4">Aset</th>
                    <th class="px-6 py-4">Deskripsi</th>
                    <th class="px-6 py-4">Biaya</th>
                    <th class="px-6 py-4">Tanggal Mulai</th>
                    <th class="px-6 py-4">Tanggal Selesai</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Dikerjakan Oleh</th>
                    <th class="px-6 py-4">Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $item->asset->name }} <br>
                        <span class="text-xs text-gray-400">{{ $item->asset->code }}</span>
                    </td>
                    <td class="px-6 py-4">{{ Str::limit($item->description, 50) }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($item->cost, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">{{ $item->start_date }}</td>
                    <td class="px-6 py-4">{{ $item->completion_date ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $item->performed_by ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $item->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-400">Belum ada data perbaikan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $maintenances->links() }}
    </div>
</div>
@endsection
