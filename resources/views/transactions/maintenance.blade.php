@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Form Laporan Perbaikan</h1>
        <p class="text-gray-500 mt-1">Laporkan kerusakan aset untuk pengajuan perbaikan.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('transactions.store.maintenance') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Aset yang Rusak</label>
                    <div class="relative">
                        <select name="asset_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 py-3 pl-4 pr-10" required>
                            <option value="">-- Cari Nama / Kode Aset --</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }} - {{ $asset->code }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                             <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Aset (Ruangan)</label>
                    <input type="text" name="room" class="w-full rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500" required placeholder="Contoh: Ruang Meeting Lt. 2">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Garansi</label>
                    <select name="warranty_status" class="w-full rounded-lg border-gray-300" required>
                        <option value="Ada">Masih Ada Garansi</option>
                        <option value="Tidak">Tidak Ada Garansi</option>
                        <option value="Habis">Garansi Habis</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi Biaya</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500 font-bold">Rp</span>
                        <input type="number" name="cost" class="w-full pl-10 rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500" required placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Kerusakan</label>
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" required>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kerusakan</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500" required placeholder="Jelaskan detail kerusakan dan kronologi kejadian jika ada..."></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-yellow-500 text-white rounded-xl shadow-lg hover:bg-yellow-600 font-bold transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-tools mr-2"></i> Kirim Laporan Perbaikan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
