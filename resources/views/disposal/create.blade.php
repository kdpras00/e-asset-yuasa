@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Input Data Pemusnahan</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('disposal.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Asset Selection -->
                <div>
                    <label for="asset_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Aset</label>
                    <select name="asset_id" id="asset_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                        <option value="">-- Pilih Aset --</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->name }} - {{ $asset->code }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Pemusnahan</label>
                    <textarea name="reason" id="reason" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Disposal Date -->
                    <div>
                        <label for="disposal_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pemusnahan</label>
                        <input type="date" name="disposal_date" id="disposal_date" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                    </div>

                    <!-- Method -->
                    <div>
                        <label for="method" class="block text-sm font-medium text-gray-700 mb-2">Metode Pemusnahan</label>
                        <select name="method" id="method" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                            <option value="sale">Penjualan (Sale)</option>
                            <option value="scrap">Scrap / Besi Tua</option>
                            <option value="destroyed">Dihancurkan (Destroyed)</option>
                            <option value="donation">Donasi</option>
                        </select>
                    </div>
                </div>

                <!-- Approved By -->
                <div>
                    <label for="approved_by" class="block text-sm font-medium text-gray-700 mb-2">Disetujui Oleh</label>
                    <input type="text" name="approved_by" id="approved_by" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="notes" id="notes" rows="2" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"></textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('disposal.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm">Simpan & Musnahkan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
