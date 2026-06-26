@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-[#0A1A32] tracking-tight mb-2">Input Data Pemusnahan</h1>
            <p class="text-gray-500 text-lg">Pengajuan penghapusan aset yang rusak total atau habis nilai guna.</p>
        </div>
    </div>

    <form action="{{ route('disposal.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-3xl shadow-sm shadow-gray-200/50 border border-gray-100 overflow-hidden relative mb-8">

            <div class="p-8 md:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Asset Selection -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Aset <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="items[0][asset_id]" class="w-full rounded-xl border-gray-300 bg-gray-50 py-3.5 pl-5 pr-10 focus:bg-white focus:ring-2 focus:ring-red-100 focus:border-red-500 transition-all font-medium text-gray-800" required>
                                    <option value="">-- Cari Nama / Kode Aset --</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}">{{ $asset->name }} - {{ $asset->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Pemusnahan <span class="text-red-500">*</span></label>
                            <textarea name="items[0][description]" rows="4" 
                                      class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:ring-2 focus:ring-red-100 focus:border-red-500 transition-all resize-none font-medium" 
                                      required placeholder="Jelaskan secara detail alasan aset ini dimusnahkan (misal: rusak total, terbakar, dll)..."></textarea>
                        </div>
                    </div>

                    <!-- Right Column: Image Upload -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Foto Kondisi Terakhir <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <input type="file" name="items[0][image]" id="file_disposal" class="hidden" required onchange="document.getElementById('preview_disposal_text').innerText = this.files[0].name">
                                <label for="file_disposal" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-red-300 rounded-2xl cursor-pointer bg-red-50/30 hover:bg-red-50 transition-all group-hover:border-red-500">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-sm">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                        <p class="mb-1 text-sm text-gray-700 font-bold">Upload Foto Barang</p>
                                        <p id="preview_disposal_text" class="text-xs text-red-400 font-medium">Wajib menyertakan bukti fisik</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="flex justify-end">
            <a href="{{ route('disposal.index') }}" class="px-8 py-3.5 text-gray-400 hover:text-gray-600 font-medium text-sm transition-colors mr-4 flex items-center">Batal</a>
            <button type="submit" class="px-10 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-2xl shadow-sm shadow-red-500/30 hover:shadow-red-500/50 hover:-translate-y-1 transition-all font-bold text-lg flex items-center gap-3">
                <i class="fas fa-trash-alt"></i>
                Ajukan Pemusnahan
            </button>
        </div>
    </form>
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
</script>
@endsection
