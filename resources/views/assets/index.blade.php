@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
           <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Daftar Aset</h1>
           <p class="text-gray-500 mt-1">Kelola dan lacak semua aset perusahaan.</p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
             <!-- Search Form -->
            <form action="{{ route('assets.index') }}" method="GET" class="flex-1 md:w-64 relative">
                <i class="fas fa-search text-gray-400 absolute left-3 top-3"></i>
                <input type="text" name="search" placeholder="Cari aset..." value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-xl border-none shadow-sm focus:ring-2 focus:ring-blue-900 bg-white text-gray-600 transition-shadow">
            </form>
            
            <!-- Export Button (All Roles) -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow-lg flex items-center transition-transform hover:-translate-y-0.5">
                    <i class="fas fa-file-export mr-2"></i> Export
                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                </button>
                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl z-50 overflow-hidden border border-gray-100" x-cloak>
                    <a href="{{ route('assets.index', ['export' => 'excel', 'type' => 'all']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-green-600">
                        <i class="fas fa-list mr-2"></i> Semua Data Aset
                    </a>
                    <a href="{{ route('assets.index', ['export' => 'excel', 'type' => 'maintenance']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-yellow-600">
                        <i class="fas fa-tools mr-2"></i> Data Perbaikan
                    </a>
                    <a href="{{ route('assets.index', ['export' => 'excel', 'type' => 'disposal']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600">
                        <i class="fas fa-trash-alt mr-2"></i> Data Pemusnahan
                    </a>
                </div>
            </div>
            


            @if(!in_array(Auth::user()->role, ['pimpinan', 'hrd']))
            <a href="{{ route('transactions.purchase') }}" class="bg-[#9E3E3E] hover:bg-[#7a2e2e] text-white font-bold py-2 px-6 rounded-xl shadow-lg flex items-center transition-transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i> Tambah Baru
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-md mb-6 flex justify-between items-center">
            <div>
                 <p class="font-bold">Berhasil</p>
                 <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-600 hover:text-green-800"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-[20px] overflow-hidden border border-gray-100">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Info Aset</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Kode SAP</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Divisi</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Stok</th>
                    @if(Auth::user()->role != 'pimpinan')
                    <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assets as $index => $asset)
                <tr class="hover:bg-blue-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                             <div class="flex-shrink-0 h-12 w-12 relative">
                                @if($asset->image)
                                    <img class="h-12 w-12 rounded-xl object-cover shadow-sm border border-gray-200 bg-white" src="{{ Storage::url($asset->image) }}" alt="">
                                @else
                                     <div class="h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                         <i class="fas fa-image"></i>
                                     </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900">{{ $asset->name }}</div>
                                <div class="text-xs text-gray-500">{{ $asset->category }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                         {{ $asset->sap_code ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                         {{ $asset->department ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusClasses = [
                                'baik' => 'bg-green-100 text-green-800',
                                'rusak' => 'bg-red-100 text-red-800',
                                'maintenance' => 'bg-yellow-100 text-yellow-800',
                                'disposed' => 'bg-gray-100 text-gray-800',
                            ];
                            $statusClass = $statusClasses[$asset->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($asset->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $asset->quantity }}
                    </td>
                    <td class="px-6 py-4 text-sm font-bold {{ $asset->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $asset->stock }}
                    </td>
                    @if(Auth::user()->role != 'pimpinan')
                    <td class="px-6 py-4 text-right">
                         <div class="flex justify-end gap-2">
                            <a href="{{ route('assets.show', $asset->id) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="View">
                                <i class="fas fa-eye text-lg"></i>
                            </a>
                            
                            @if(Auth::user()->role != 'hrd')
                            <a href="{{ route('assets.edit', $asset->id) }}" class="text-gray-400 hover:text-green-600 transition-colors" title="Edit">
                                <i class="fas fa-edit text-lg"></i>
                            </a>
                            <button type="button" onclick="confirmDelete('{{ $asset->id }}')" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                <i class="fas fa-trash-alt text-lg"></i>
                            </button>
                            <form id="delete-form-{{ $asset->id }}" action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                 <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                            <p>Tidak ada aset ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $assets->withQueryString()->links() }}
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikannya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection
