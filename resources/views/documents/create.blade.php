@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
     <h1 class="text-3xl font-bold text-gray-800">Upload New Dokumen</h1>
     <!-- Action Icons -->
     <div class="text-gray-600">
         <i class="fas fa-calendar-alt text-2xl"></i>
     </div>
</div>

<div class="bg-white p-8 rounded-[30px] shadow-lg max-w-5xl mx-auto">
    <form action="{{ route('assets.documents.store', $asset->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-4">
            <!-- Read-only Info -->
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-4 items-center">
                <label class="text-lg text-gray-800 font-medium">Kode Sap</label>
                <input type="text" value="{{ $asset->sap_code }}" class="w-full bg-gray-200 border-none rounded-lg py-3 px-4 focus:ring-0 text-gray-600" readonly>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-4 items-center">
                <label class="text-lg text-gray-800 font-medium">Nama Asset</label>
                <input type="text" value="{{ $asset->name }}" class="w-full bg-gray-200 border-none rounded-lg py-3 px-4 focus:ring-0 text-gray-600" readonly>
            </div>

             <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-4 items-center">
                <label class="text-lg text-gray-800 font-medium">Kategori Asset</label>
                <input type="text" value="{{ $asset->category }}" class="w-full bg-gray-200 border-none rounded-lg py-3 px-4 focus:ring-0 text-gray-600" readonly>
            </div>

            <!-- Upload Fields -->
             <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-4 items-center">
                <label class="text-lg text-gray-800 font-medium">Dokumen Pemusnahan</label>
                <div>
                     <input type="file" name="disposal" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-gray-200 file:text-gray-700
                        hover:file:bg-gray-300
                      " accept="application/pdf">
                </div>
            </div>

             <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-4 items-center">
                <label class="text-lg text-gray-800 font-medium">Dokumen Pembelian</label>
                <div>
                     <input type="file" name="purchase" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-gray-200 file:text-gray-700
                        hover:file:bg-gray-300
                      " accept="application/pdf">
                </div>
            </div>

             <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-4 items-center">
                <label class="text-lg text-gray-800 font-medium">Dokumen Pemeliharaan</label>
                <div>
                     <input type="file" name="maintenance" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-gray-200 file:text-gray-700
                        hover:file:bg-gray-300
                      " accept="application/pdf">
                </div>
            </div>

            <!-- Optional: Update Photo or Date here as per design -->
             <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-4 items-center">
                <label class="text-lg text-gray-800 font-medium">Foto Asset</label>
                <div>
                     <!-- Not implementing photo upload in doc upload page unless strictly required, keeping it read only or optional -->
                      <input type="file" name="image" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-gray-200 file:text-gray-700
                        hover:file:bg-gray-300
                      ">
                </div>
            </div>

             <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] gap-4 items-center">
                <label class="text-lg text-gray-800 font-medium">ACQ date</label>
                <input type="date" name="acq_date" value="{{ $asset->purchase_date }}" class="w-full md:w-1/3 bg-gray-200 border-none rounded-lg py-3 px-4 focus:ring-0">
            </div>

        </div>

        <div class="flex justify-end mt-10">
             <button type="submit" class="bg-[#0A1A32] text-white font-bold py-3 px-8 rounded-[15px] hover:bg-[#162d4d] transition duration-200">
                Upload Here
            </button>
        </div>
    </form>
</div>
@endsection
