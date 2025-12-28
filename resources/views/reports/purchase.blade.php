@extends('layouts.app')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-extrabold text-[#0A1A32] tracking-tight">Laporan Pembelian Aset</h1>
            <p class="text-gray-500 mt-1 text-lg">Daftar semua aset yang telah dibeli dan terdaftar dalam sistem.</p>
        </div>
        @if(Auth::user()->role != 'hrd')
        <a href="{{ route('transactions.purchase') }}" 
           class="bg-gradient-to-r from-blue-700 to-blue-600 text-white px-8 py-3.5 rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all font-bold flex items-center gap-2">
            <i class="fas fa-plus-circle text-lg"></i> 
            <span>Input Pembelian Baru</span>
        </a>
        @endif
    </div>

    <!-- Quick Stats Row (Optional, nice to have) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-box-open"></i>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase font-bold tracking-wider">Total Aset</p>
                <p class="text-2xl font-extrabold text-gray-800">{{ $assets->total() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
             <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl mr-4">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                 <p class="text-gray-500 text-xs uppercase font-bold tracking-wider">Total Nilai</p>
                 <!-- Note: sum() acts on the query builder or collection, paginate returns LengthAwarePaginator. 
                      Since we only passed paginated results, we might not have total sum here easily without another query.
                      Let's leave it simple or remove if complex. For now just placeholder or we can pass it from controller. -->
                 <p class="text-xl font-bold text-gray-800">Rp {{ number_format(\App\Models\Asset::sum('price'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>


    <div class="bg-white shadow-xl shadow-gray-200/50 rounded-3xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Info Aset
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Tgl Pembelian
                        </th>
                         <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($assets as $asset)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden border border-gray-200">
                                    <img class="w-full h-full object-cover" 
                                         src="{{ $asset->image ? asset('storage/'.$asset->image) : 'https://ui-avatars.com/api/?name='.urlencode($asset->name).'&background=random' }}" 
                                         alt="{{ $asset->name }}">
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $asset->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $asset->code }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600">
                                {{ $asset->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                            {{ $asset->created_at->format('d M Y') }}
                        </td>
                         <td class="px-6 py-4 text-sm font-bold text-gray-800">
                            Rp {{ number_format($asset->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-700',
                                    'maintenance' => 'bg-yellow-100 text-yellow-700',
                                    'disposed' => 'bg-red-100 text-red-700',
                                    'missing' => 'bg-gray-100 text-gray-700',
                                    'pending' => 'bg-blue-100 text-blue-700',
                                    'rejected' => 'bg-red-200 text-red-800',
                                ];
                                $colorClass = $statusColors[$asset->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $colorClass }}">
                                {{ ucfirst($asset->status == 'pending' ? 'Menunggu Approval' : $asset->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($asset->status == 'pending' && trim(Auth::user()->role) == 'pimpinan')
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('reports.purchase.approve', $asset->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-1.5 px-3 rounded shadow transition-all" title="Setujui">
                                            <i class="fas fa-check"></i> ACC
                                        </button>
                                    </form>
                                    <form action="{{ route('reports.purchase.reject', $asset->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1.5 px-3 rounded shadow transition-all" title="Tolak">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('assets.show', $asset->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm">
                                    Detail <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada data pembelian aset.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $assets->links() }}
        </div>
    </div>
@endsection
