@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Laporan Pemusnahan Aset</h1>
        <p class="text-gray-500 mt-1">Daftar aset yang telah dimusnahkan atau dihapus.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('disposal.create') }}" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm">
            <i class="fas fa-plus"></i>
            <span class="font-medium text-sm">Tambah Data</span>
        </a>
        <a href="{{ route('reports.export', ['format' => 'excel', 'type' => 'disposal']) }}" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
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
                    <th class="px-6 py-4">Alasan</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4">Tanggal Pemusnahan</th>
                    <th class="px-6 py-4">Disetujui Oleh</th>
                    <th class="px-6 py-4">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($disposals as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $item->asset->name }} <br>
                        <span class="text-xs text-gray-400">{{ $item->asset->code }}</span>
                    </td>
                    <td class="px-6 py-4">{{ Str::limit($item->reason, 50) }}</td>
                    <td class="px-6 py-4">{{ ucfirst($item->method) }}</td>
                    <td class="px-6 py-4">{{ $item->disposal_date }}</td>
                    <td class="px-6 py-4">{{ $item->approved_by ?? '-' }}</td>
                    <td class="px-6 py-4">{{ Str::limit($item->notes, 30) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-400">Belum ada data pemusnahan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $disposals->links() }}
    </div>
</div>
@endsection
