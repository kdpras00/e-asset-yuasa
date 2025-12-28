@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-4xl font-extrabold text-[#0A1A32] tracking-tight mb-2">Form Pembelian Aset</h1>
        <p class="text-gray-500 text-lg">Input data pengadaan aset baru untuk inventaris perusahaan.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative">
        <div class="absolute top-0 w-full h-2 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        
        <form action="{{ route('transactions.store.purchase') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
            @csrf
            
            <div class="space-y-8">
                <!-- Section 1: Identitas Aset -->
                <div>
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">Identitas Aset</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Aset <span class="text-red-500">*</span></label>
                            <input type="text" name="name" 
                                   class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium" 
                                   required placeholder="Contoh: Laptop Dell XPS 13" value="{{ old('name') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kode Aset / SAP Code</label>
                            <input type="text" name="code" 
                                   class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-3 text-gray-500 cursor-not-allowed font-mono" 
                                   readonly placeholder="Digenarate Otomatis (Auto-generated)">
                            <p class="text-xs text-gray-400 mt-1">Kode aset akan dibuat otomatis oleh sistem.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="category" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 appearance-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Elektronik" {{ old('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                    <option value="Furniture" {{ old('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="Kendaraan" {{ old('category') == 'Kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                                    <option value="Mesin" {{ old('category') == 'Mesin' ? 'selected' : '' }}>Mesin</option>
                                    <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ruangan / Divisi <span class="text-red-500">*</span></label>
                            <input type="text" name="department" 
                                   class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium" 
                                   required placeholder="Contoh: IT Dept / Ruang Server" value="{{ old('department') }}">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Detail Pembelian -->
                <div>
                     <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">Detail Pembelian</h3>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Harga Pembelian <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-400 font-bold">Rp</span>
                                <input type="number" name="price" 
                                       class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-gray-700" 
                                       required placeholder="0" value="{{ old('price') }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Unit <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" 
                                   class="w-full px-4 py-3 rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-bold text-gray-700 text-center" 
                                   required>
                        </div>
                        <div class="md:col-span-2">
                             <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi & Spesifikasi <span class="text-red-500">*</span></label>
                             <textarea name="description" rows="3" 
                                       class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all resize-none font-medium" 
                                       required placeholder="Tambahkan detail spesifikasi teknis atau catatan penting...">{{ old('description') }}</textarea>
                        </div>
                     </div>
                </div>

                <!-- Section 3: Upload Dokumen -->
                <div>
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">Dokumen & Foto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative group">
                            <input type="file" name="image" id="file_image" class="hidden" required onchange="document.getElementById('preview_image_text').innerText = this.files[0].name">
                            <label for="file_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-blue-300 rounded-2xl cursor-pointer bg-blue-50/30 hover:bg-blue-50 transition-all group-hover:border-blue-500">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <p class="mb-1 text-sm text-gray-700 font-bold">Foto Aset Fisik <span class="text-red-500">*</span></p>
                                    <p id="preview_image_text" class="text-xs text-gray-400">klik untuk upload</p>
                                </div>
                            </label>
                        </div>
                        <div class="relative group">
                             <input type="file" name="warranty_proof" id="file_warranty" class="hidden" onchange="document.getElementById('preview_warranty_text').innerText = this.files[0].name">
                             <label for="file_warranty" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-purple-300 rounded-2xl cursor-pointer bg-purple-50/30 hover:bg-purple-50 transition-all group-hover:border-purple-500">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-10 h-10 bg-purple-100 text-purple-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <p class="mb-1 text-sm text-gray-700 font-bold">Kartu Garansi</p>
                                    <p id="preview_warranty_text" class="text-xs text-gray-400">opsional</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs -->
                <input type="hidden" name="status" value="active">
                <input type="hidden" name="type" value="fixed">
            </div>

            <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600 font-medium text-sm transition-colors">Batal</a>
                <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-blue-700 to-blue-600 text-white rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all font-bold flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Simpan Aset Baru
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
            confirmButtonColor: '#3085d6',
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
