@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
         <h1 class="text-3xl font-bold text-[#0A1A32]">Detail Asset</h1>
         <p class="text-gray-500 text-sm">View complete specifications and history.</p>
    </div>
    <div class="flex gap-3">
         <button onclick="window.print()" class="bg-white text-gray-700 hover:text-gray-900 border px-4 py-2 rounded-lg shadow-sm font-medium transition-all hover:shadow-md">
            <i class="fas fa-print mr-2"></i> Print
        </button>
        <a href="{{ route('assets.edit', $asset->id) }}" class="bg-[#0A1A32] text-white px-6 py-2 rounded-lg shadow-md font-medium hover:bg-[#152a4d] transition-all hover:-translate-y-0.5">
            <i class="fas fa-edit mr-2"></i> Edit Asset
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
                        <span class="text-xs">No Image Available</span>
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-100 pt-4">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-file-alt text-[#9E3E3E] mr-2"></i> Linked Documents
                </h3>
                <div class="space-y-2">
                     @forelse($asset->documents as $doc)
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="block p-3 bg-gray-50 rounded-lg hover:bg-red-50 hover:border-red-100 border border-transparent transition-all group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-500 mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                                    <div>
                                         <p class="text-sm font-semibold text-gray-700 capitalize">{{ $doc->type }}</p>
                                         <p class="text-[10px] text-gray-400">{{ basename($doc->file_path) }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $doc->status == 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($doc->status) }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-gray-500 italic pl-1">No documents attached.</p>
                    @endforelse
                </div>
                 <a href="{{ route('assets.documents.create', $asset->id) }}" class="mt-4 flex items-center justify-center w-full border-2 border-dashed border-gray-300 text-gray-500 py-3 rounded-lg hover:border-[#9E3E3E] hover:text-[#9E3E3E] transition-colors text-sm font-semibold">
                    <i class="fas fa-plus mr-2"></i> Upload New Document
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Details Table -->
    <div class="w-full lg:w-2/3">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 h-full relative">
            <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-4">Asset Specifications</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700">
                    <tbody class="divide-y divide-gray-50">
                        @foreach([
                            'Kode Sap' => $asset->sap_code ?? '-',
                            'Kategori Asset' => $asset->category,
                            'Grup' => $asset->group ?? '-',
                            'Sub Grup' => '-', 
                            'Department' => $asset->department ?? '-',
                            'Section' => $asset->section ?? '-',
                            'Email Pengguna' => Auth::user()->email,
                            'Catatan Asset' => $asset->description,
                            'Status Asset' => ucfirst($asset->status),
                            'Nilai Asset' => 'Rp ' . number_format($asset->price, 0, ',', '.'),
                            'Nilai Pengguna Asset' => '0',
                            'Nilai Asset Saat Ini' => '0',
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
                    </tbody>
                </table>
            </div>

            <!-- Barcode Section -->
            <div class="mt-12 flex flex-col items-center justify-center pt-8 border-t border-dashed border-gray-200">
                <p class="mb-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Asset Barcode</p>
                <div class="p-4 bg-white border rounded-lg shadow-sm">
                    <svg id="barcode"></svg>
                </div>
                <p class="mt-2 text-xs font-mono text-gray-500">{{ $asset->sap_code ?? $asset->code }}</p>
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
        displayValue: false
    });
</script>
@endsection
