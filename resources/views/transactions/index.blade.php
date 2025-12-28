@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ activeTab: '{{ request()->query('tab', 'pembelian') }}', rowCount: 1 }">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Input Laporan & Transaksi</h1>
        <p class="text-gray-500 mt-1">Pusat input data pembelian aset, perbaikan, dan pemusnahan.</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex gap-4 mb-6 border-b border-gray-200">
        <button @click="activeTab = 'pembelian'" 
                :class="{ 'border-blue-600 text-blue-600': activeTab === 'pembelian', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'pembelian' }"
                class="pb-3 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-shopping-cart mr-2"></i> Pembelian (Aset Baru)
        </button>
        <button @click="activeTab = 'perbaikan'" 
                :class="{ 'border-yellow-500 text-yellow-600': activeTab === 'perbaikan', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'perbaikan' }"
                class="pb-3 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-tools mr-2"></i> Perbaikan Aset
        </button>
        <button @click="activeTab = 'pemusnahan'" 
                :class="{ 'border-red-600 text-red-600': activeTab === 'pemusnahan', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'pemusnahan' }"
                class="pb-3 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-trash-alt mr-2"></i> Pemusnahan Aset
        </button>
    </div>

    <!-- Content Area -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative min-h-[500px]">
        
        <!-- Tab 1: Pembelian -->
        <div x-show="activeTab === 'pembelian'" x-transition:enter="transition ease-out duration-300">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Form Pembelian Aset Baru</h2>
            <form action="{{ route('transactions.store.purchase') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aset</label>
                        <input type="text" name="name" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Aset / SAP Code</label>
                        <input type="text" name="code" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required placeholder="Auto-generated IF empty">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category" class="w-full rounded-lg border-gray-300" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Kendaraan">Kendaraan</option>
                            <option value="Mesin">Mesin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan / Divisi</label>
                        <input type="text" name="department" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required placeholder="Contoh: IT Dept / Ruang Server">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kisaran Harga Pembelian</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" name="price" class="w-full pl-10 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Quantity)</label>
                        <input type="number" name="quantity" value="1" min="1" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Barang</label>
                        <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Garansi</label>
                        <input type="file" name="warranty_proof" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <!-- Hidden Status Active -->
                    <input type="hidden" name="status" value="active">
                    <input type="hidden" name="type" value="fixed">
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Kirim Data Pembelian</button>
                </div>
            </form>
        </div>

        <!-- Tab 2: Perbaikan -->
        <div x-show="activeTab === 'perbaikan'" x-cloak x-transition:enter="transition ease-out duration-300">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Form Laporan Perbaikan</h2>
            <form action="{{ route('transactions.store.maintenance') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Aset</label>
                        <select name="asset_id" class="w-full rounded-lg border-gray-300" required>
                            <option value="">-- Cari Aset --</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }} - {{ $asset->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan / Divisi</label>
                        <input type="text" name="room" class="w-full rounded-lg border-gray-300" required placeholder="Lokasi aset saat ini">
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Garansi</label>
                        <select name="warranty_status" class="w-full rounded-lg border-gray-300" required>
                            <option value="Ada">Ada</option>
                            <option value="Tidak">Tidak Ada</option>
                            <option value="Habis">Habis (Expired)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi Biaya Perbaikan</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" name="cost" class="w-full pl-10 rounded-lg border-gray-300" required>
                        </div>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti Kerusakan</label>
                        <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kerusakan</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300" required></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium">Kirim Laporan Perbaikan</button>
                </div>
            </form>
        </div>

        <!-- Tab 3: Pemusnahan (Bulk) -->
         <div x-show="activeTab === 'pemusnahan'" x-cloak x-transition:enter="transition ease-out duration-300">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Form Pemusnahan Aset</h2>
            <form action="{{ route('transactions.store.disposal') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-4 mb-6">
                    <template x-for="(i, index) in rowCount" :key="index">
                        <div class="p-4 border border-gray-200 rounded-xl bg-gray-50 relative">
                            <h3 class="text-sm font-bold text-gray-500 mb-3">Item #<span x-text="index + 1"></span></h3>
                            
                            <!-- Remove Button -->
                            <button type="button" @click="if(rowCount > 1) rowCount--" x-show="rowCount > 1" class="absolute top-4 right-4 text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Aset</label>
                                    <select :name="'items['+index+'][asset_id]'" class="w-full rounded-lg border-gray-300 text-sm" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($assets as $asset)
                                            <option value="{{ $asset->id }}">{{ $asset->name }} - {{ $asset->code }}</option>
                                        @endforeach
                                    </select>
                                    <!-- Hidden Name field for fallback if needed, but we use ID -->
                                    <input type="hidden" :name="'items['+index+'][name]'" value="MappedFromID"> 
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Bukti Kondisi (Foto)</label>
                                    <input type="file" :name="'items['+index+'][image]'" class="w-full text-xs text-gray-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Alasan Pemusnahan</label>
                                    <input type="text" :name="'items['+index+'][description]'" class="w-full rounded-lg border-gray-300 text-sm" required>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" @click="rowCount++" class="text-sm text-blue-600 font-medium hover:underline">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Baris Aset
                    </button>
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Kirim Laporan Pemusnahan</button>
                </div>
            </form>
         </div>

    </div>
</div>
@endsection
