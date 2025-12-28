@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-4xl font-extrabold text-[#0A1A32] tracking-tight mb-2">Form Laporan Perbaikan</h1>
        <p class="text-gray-500 text-lg">Ajukan perbaikan untuk aset yang mengalami kerusakan.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative">
        <div class="absolute top-0 w-full h-2 bg-gradient-to-r from-yellow-500 to-orange-500"></div>

        <form action="{{ route('transactions.store.maintenance') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left Column: Asset Selection & Status -->
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Aset yang Rusak <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="asset_id" class="w-full rounded-xl border-gray-300 bg-gray-50 py-3.5 pl-5 pr-10 focus:bg-white focus:ring-2 focus:ring-yellow-100 focus:border-yellow-500 transition-all font-medium text-gray-800" required>
                                <option value="">-- Cari Nama / Kode Aset --</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->name }} - {{ $asset->code }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                 <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi Aset (Ruangan) <span class="text-red-500">*</span></label>
                            <input type="text" name="room" 
                                   class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:ring-2 focus:ring-yellow-100 focus:border-yellow-500 transition-all font-medium" 
                                   required placeholder="Contoh: Ruang Meeting Lt. 2">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status Garansi <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="warranty_status" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 appearance-none focus:bg-white focus:ring-2 focus:ring-yellow-100 focus:border-yellow-500 transition-all font-medium" required>
                                    <option value="Ada">Masih Ada Garansi</option>
                                    <option value="Tidak">Tidak Ada Garansi</option>
                                    <option value="Habis">Garansi Habis</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" 
                                  class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:ring-2 focus:ring-yellow-100 focus:border-yellow-500 transition-all resize-none font-medium" 
                                  required placeholder="Jelaskan detail kerusakan dan kronologi kejadian jika ada..."></textarea>
                    </div>
                </div>

                <!-- Right Column: Cost & Image -->
                <div class="space-y-6">
                    <div class="bg-yellow-50 rounded-2xl p-6 border border-yellow-200">
                        <label class="block text-xs font-bold text-yellow-800 uppercase tracking-wider mb-2">Estimasi Biaya <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-3.5 text-yellow-600 font-bold text-lg">Rp</span>
                            <input type="number" name="cost" 
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border-yellow-300 bg-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 font-bold text-xl text-gray-800" 
                                   required placeholder="0">
                        </div>
                        <p class="text-[10px] text-yellow-600 mt-2 leading-tight">Biaya perkiraan awal, dapat berubah setelah pengecekan teknis.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Kerusakan <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <input type="file" name="image" id="file_repair" class="hidden" required onchange="document.getElementById('preview_repair_text').innerText = this.files[0].name">
                            <label for="file_repair" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-red-300 rounded-2xl cursor-pointer bg-red-50/30 hover:bg-red-50 transition-all group-hover:border-red-500">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-sm">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <p class="mb-1 text-sm text-gray-700 font-bold">Upload Bukti</p>
                                    <p id="preview_repair_text" class="text-xs text-red-400 font-medium">Wajib diisi</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-xl shadow-lg shadow-yellow-500/30 hover:shadow-yellow-500/50 hover:-translate-y-0.5 transition-all font-bold flex items-center gap-2">
                    <i class="fas fa-tools"></i>
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Silakan periksa kembali inputan Anda.',
            html: `
                <ul style="text-align: left; margin-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `
        });
    @endif
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6'
        });
    @endif
</script>
@endsection
