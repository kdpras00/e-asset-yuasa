@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ rowCount: 1 }">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Form Pemusnahan Aset</h1>
        <p class="text-gray-500 mt-1">Pengajuan pemusnahan untuk aset yang rusak total atau sudah tidak bernilai guna.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('transactions.store.disposal') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6 mb-8">
                <!-- Header -->
                <div class="grid grid-cols-12 gap-4 text-sm font-bold text-gray-500 uppercase tracking-wider pb-2 border-b border-gray-100 hidden md:grid">
                    <div class="col-span-4">Pilih Aset</div>
                    <div class="col-span-3">Bukti Kondisi (Foto)</div>
                    <div class="col-span-4">Alasan Pemusnahan</div>
                    <div class="col-span-1 border-l border-gray-200 pl-4 text-center">#</div>
                </div>

                <!-- Rows -->
                <template x-for="(i, index) in rowCount" :key="index">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start pb-6 border-b border-gray-50 last:border-0 relative bg-gray-50 md:bg-transparent p-4 md:p-0 rounded-xl md:rounded-none mb-4 md:mb-0">
                        <!-- Mobile Label -->
                        <div class="md:hidden font-bold text-gray-800 mb-2 col-span-12">Item #<span x-text="index + 1"></span></div>

                        <!-- Asset Select -->
                        <div class="col-span-12 md:col-span-4">
                            <label class="md:hidden text-xs text-gray-500 font-bold uppercase mb-1 block">Pilih Aset</label>
                            <select :name="'items['+index+'][asset_id]'" class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500" required>
                                <option value="">-- Pilih Aset --</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->name }} - {{ $asset->code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Image Upload -->
                        <div class="col-span-12 md:col-span-3">
                            <label class="md:hidden text-xs text-gray-500 font-bold uppercase mb-1 block">Bukti Foto</label>
                            <input type="file" :name="'items['+index+'][image]'" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" required>
                        </div>

                        <!-- Description -->
                        <div class="col-span-12 md:col-span-4">
                            <label class="md:hidden text-xs text-gray-500 font-bold uppercase mb-1 block">Alasan</label>
                            <input type="text" :name="'items['+index+'][description]'" class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500" placeholder="Rusak total / Hilang / Expired" required>
                        </div>

                        <!-- Remove Action -->
                        <div class="col-span-12 md:col-span-1 flex justify-center md:items-start md:pt-2">
                             <button type="button" @click="if(rowCount > 1) rowCount--" x-show="rowCount > 1" class="text-red-400 hover:text-red-700 transition-colors p-2 rounded-full hover:bg-red-50">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <button type="button" @click="rowCount++" class="w-full md:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors border border-gray-200">
                    <i class="fas fa-plus-circle mr-2 text-blue-500"></i> Tambah Item Lain
                </button>

                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-red-600 text-white rounded-xl shadow-lg hover:bg-red-700 font-bold transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Ajukan Pemusnahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
