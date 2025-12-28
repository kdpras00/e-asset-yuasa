@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Form Pembelian Aset Baru</h1>
        <p class="text-gray-500 mt-1">Input data pengadaan atau pembelian aset baru.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('transactions.store.purchase') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aset</label>
                        <input type="text" name="name" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required placeholder="Contoh: Laptop Lenovo Thinkpad">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Aset / SAP Code</label>
                        <input type="text" name="code" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required placeholder="Auto-generated IF empty">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category" class="w-full rounded-lg border-gray-300" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Kendaraan">Kendaraan</option>
                            <option value="Mesin">Mesin</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan / Divisi</label>
                        <input type="text" name="department" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required placeholder="Contoh: IT Dept / Ruang Server">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                </div>

                <!-- Images -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Barang</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col w-full h-32 border-2 border-blue-200 border-dashed rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-7">
                                    <i class="fas fa-camera text-blue-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">Upload Foto Aset</p>
                                </div>
                                <input type="file" name="image" class="opacity-0" required />
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Garansi</label>
                         <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col w-full h-32 border-2 border-purple-200 border-dashed rounded-lg cursor-pointer hover:bg-purple-50 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-7">
                                    <i class="fas fa-certificate text-purple-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">Upload Kartu Garansi</p>
                                </div>
                                <input type="file" name="warranty_proof" class="opacity-0" />
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Tambahkan detail spesifikasi atau catatan tambahan..."></textarea>
                </div>

                <!-- Hidden inputs -->
                <input type="hidden" name="status" value="active">
                <input type="hidden" name="type" value="fixed">
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl shadow-lg hover:bg-blue-700 font-bold transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Data Pembelian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
