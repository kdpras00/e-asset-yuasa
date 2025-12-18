@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
         <h1 class="text-3xl font-bold text-[#0A1A32]">Detail Aset</h1>
         <p class="text-gray-500 text-sm">Lihat spesifikasi lengkap dan riwayat.</p>
    </div>
    <div class="flex gap-3">
         <button onclick="window.print()" class="bg-white text-gray-700 hover:text-gray-900 border px-4 py-2 rounded-lg shadow-sm font-medium transition-all hover:shadow-md">
            <i class="fas fa-print mr-2"></i> Cetak
        </button>
        <a href="{{ route('assets.edit', $asset->id) }}" class="bg-[#0A1A32] text-white px-6 py-2 rounded-lg shadow-md font-medium hover:bg-[#152a4d] transition-all hover:-translate-y-0.5">
            <i class="fas fa-edit mr-2"></i> Edit Aset
        </a>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-8 min-h-screen">
    
    <!-- Left Column: Asset Info & Image -->
    <div class="w-full lg:w-1/3 space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
             <div class="flex justify-between items-start mb-4">
                 <h2 class="text-xl font-bold text-[#9E3E3E] uppercase leading-tight">{{ $asset->name }}</h2>
                 <span class="bg-blue-50 text-blue-800 text-xs px-2 py-1 rounded font-bold uppercase tracking-wide">{{ $asset->status }}</span>
             </div>
             
             <p class="text-xs font-bold text-gray-500 mb-4 tracking-wide">
                 <i class="fas fa-map-marker-alt mr-1"></i> {{ $asset->location ?? 'NO LOCATION' }} 
                 <span class="mx-2">•</span> 
                 <i class="fas fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($asset->created_at)->format('d M Y') }}
             </p>
            
            <div class="bg-gray-100 p-2 rounded-xl mb-6 border border-gray-200 shadow-inner">
                 @if($asset->image)
                    <img src="{{ Storage::url($asset->image) }}" alt="Asset Image" class="w-full h-auto rounded-lg object-cover transition-transform duration-500 hover:scale-[1.02]">
                @else
                    <div class="w-full h-64 flex flex-col items-center justify-center text-gray-400">
                        <i class="fas fa-camera text-4xl mb-2 opacity-50"></i>
                        <span class="text-xs">Tidak Ada Gambar</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Details Table -->
    <div class="w-full lg:w-2/3">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 h-full relative">
            <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-4">Spesifikasi Aset</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700">
                    <tbody class="divide-y divide-gray-50">
                        @foreach([
                            'Kode Sap' => $asset->sap_code ?? '-',
                            'Kategori Asset' => $asset->category,
                            // Group Removed
                            // Sub Grup Removed 
                            'Department' => $asset->department ?? '-',
                            'Section' => $asset->section ?? '-',
                            'Email Pengguna' => Auth::user()->email,
                            'Catatan Asset' => $asset->description,
                            'Status Asset' => ucfirst($asset->status),
                            'Stok Tersedia' => $asset->stock . ' Unit',
                            'Total Kuantitas' => $asset->quantity . ' Unit',
                            'Nilai Asset' => 'Rp ' . number_format($asset->price, 0, ',', '.'),
                            'ACQ. Date' => $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('d M Y') : '-',
                            'Kode Center' => $asset->code,
                            'PIC' => $asset->pic ?? '-'
                        ] as $label => $value)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 w-1/3 font-medium text-gray-500">{{ $label }}</td>
                            <td class="py-3 w-5 text-center text-gray-300">:</td>
                            <td class="py-3 font-semibold text-gray-800">{{ $value }}</td>
                        </tr>
                        @endforeach
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 w-1/3 font-medium text-gray-500">Lokasi</td>
                            <td class="py-3 w-5 text-center text-gray-300">:</td>
                            <td class="py-3 font-semibold text-gray-800">{{ $asset->location ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
<script>
    JsBarcode("#barcode", "{{ $asset->sap_code ?? $asset->code }}", {
        format: "CODE128",
        lineColor: "#0A1A32",
        width: 2,
        height: 60,
        displayValue: true
    });
</script>
@endsection
