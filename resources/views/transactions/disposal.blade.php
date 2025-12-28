@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ rowCount: 1 }">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-[#0A1A32] tracking-tight mb-2">Form Pemusnahan Aset</h1>
            <p class="text-gray-500 text-lg">Pengajuan penghapusan aset yang rusak total atau habis nilai guna.</p>
        </div>
        <div class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold border border-red-100 flex items-center shadow-sm">
            <i class="fas fa-info-circle mr-2"></i> Mode Bukalapak (Bulk Input)
        </div>
    </div>

    <form action="{{ route('transactions.store.disposal') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative mb-8">
            <div class="absolute top-0 w-full h-2 bg-gradient-to-r from-red-600 to-pink-600"></div>
            
            <div class="p-6 md:p-8">
                <!-- Table Header (Desktop) -->
                <div class="hidden md:grid grid-cols-12 gap-6 text-xs font-bold text-gray-400 uppercase tracking-widest pb-4 border-b border-gray-100 mb-4">
                    <div class="col-span-4 pl-2">Informasi Aset <span class="text-red-500">*</span></div>
                    <div class="col-span-3">Bukti Kondisi (Foto) <span class="text-red-500">*</span></div>
                    <div class="col-span-4">Alasan Pemusnahan <span class="text-red-500">*</span></div>
                    <div class="col-span-1 text-center">Hapus</div>
                </div>

                <!-- Rows Container -->
                <div class="space-y-4">
                    <template x-for="(i, index) in rowCount" :key="index">
                        <div class="relative bg-gray-50/50 md:bg-white p-5 md:p-2 rounded-2xl border border-gray-200 md:border-0 md:border-b md:border-dashed md:border-gray-200 last:border-0 hover:bg-gray-50 transition-colors group">
                            
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6 items-center">
                                <!-- Mobile Label -->
                                <div class="md:hidden flex justify-between items-center mb-2 pb-2 border-b border-gray-200">
                                    <span class="font-bold text-gray-800">Item #<span x-text="index + 1"></span></span>
                                    <button type="button" @click="if(rowCount > 1) rowCount--" x-show="rowCount > 1" class="text-red-500 text-sm font-medium">Hapus</button>
                                </div>
                                
                                <!-- Asset Select -->
                                <div class="col-span-12 md:col-span-4">
                                    <label class="md:hidden text-xs font-bold text-gray-500 uppercase mb-1 block">Pilih Aset</label>
                                    <div class="relative">
                                        <select :name="'items['+index+'][asset_id]'" class="w-full rounded-xl border-gray-300 text-sm py-2.5 pl-3 pr-8 focus:ring-2 focus:ring-red-100 focus:border-red-500 font-medium text-gray-700 bg-white shadow-sm transition-all" required>
                                            <option value="">-- Pilih Aset --</option>
                                            @foreach($assets as $asset)
                                                <option value="{{ $asset->id }}">{{ $asset->name }} - {{ $asset->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="col-span-12 md:col-span-3">
                                    <label class="md:hidden text-xs font-bold text-gray-500 uppercase mb-1 block">Bukti Foto</label>
                                    <input type="file" :name="'items['+index+'][image]'" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-600 hover:file:bg-red-100 cursor-pointer" required>
                                </div>

                                <!-- Reason Input -->
                                <div class="col-span-12 md:col-span-4">
                                    <label class="md:hidden text-xs font-bold text-gray-500 uppercase mb-1 block">Alasan</label>
                                    <input type="text" :name="'items['+index+'][description]'" class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:ring-2 focus:ring-red-100 focus:border-red-500 bg-white shadow-sm transition-all font-medium" placeholder="Contoh: Rusak total akibat banjir" required>
                                </div>

                                <!-- Desktop Delete Action -->
                                <div class="hidden md:flex col-span-1 justify-center">
                                     <button type="button" @click="if(rowCount > 1) rowCount--" x-show="rowCount > 1" class="w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-white hover:bg-red-500 transition-all opacity-0 group-hover:opacity-100">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Add Row Button -->
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <button type="button" @click="rowCount++" class="group flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 group-hover:border-blue-500 flex items-center justify-center mr-2 transition-colors">
                            <i class="fas fa-plus text-xs"></i>
                        </div>
                        Tambah Baris Aset Lain
                    </button>
                </div>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="flex justify-end">
            <button type="submit" class="px-10 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-2xl shadow-xl shadow-red-500/30 hover:shadow-red-500/50 hover:-translate-y-1 transition-all font-bold text-lg flex items-center gap-3">
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
