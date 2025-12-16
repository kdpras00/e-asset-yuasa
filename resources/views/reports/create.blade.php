@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Upload New Document</h1>
            <p class="text-gray-500 text-sm mt-1">Attach a new document to an asset.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="bg-white text-gray-700 hover:text-gray-900 border border-gray-300 hover:bg-gray-50 font-medium text-sm flex items-center px-4 py-2 rounded-lg shadow-sm transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-8 space-y-6">
                <!-- Asset Selection -->
                <div>
                    <label for="asset_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Asset <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="asset_id" id="asset_id" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            <option value="" disabled selected>Choose an asset...</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }} ({{ $asset->sap_code ?? $asset->code }})</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Document Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Document Type <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="type" id="type" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            <option value="" disabled selected>Select document type...</option>
                            <option value="purchase">Purchase Order (PO)</option>
                            <option value="maintenance">Maintenance Report</option>
                            <option value="disposal">Disposal Request</option>
                            <option value="other">Other Document</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- File Upload -->
                <div>
                     <label class="block text-sm font-semibold text-gray-700 mb-2">Document File <span class="text-red-500">*</span></label>
                     <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors group relative overflow-hidden">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                <i class="fas fa-file-upload text-3xl text-gray-400 group-hover:text-blue-500 transition-colors mb-3"></i>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                                <p class="text-xs text-gray-400">PDF, PNG, JPG (MAX. 2MB)</p>
                            </div>
                            <!-- Filename preview -->
                            <div id="file-name" class="hidden absolute inset-0 bg-blue-50 flex items-center justify-center text-blue-700 font-medium p-4 text-center">
                            </div>
                            <input id="dropzone-file" name="document" type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required />
                        </label>
                    </div>
                    <script>
                        const dropzone = document.getElementById('dropzone-file');
                        const fileNameDisplay = document.getElementById('file-name');
                        const placeholder = document.getElementById('upload-placeholder');

                        dropzone.addEventListener('change', function(e) {
                            if(e.target.files.length > 0) {
                                fileNameDisplay.textContent = e.target.files[0].name;
                                fileNameDisplay.classList.remove('hidden');
                                placeholder.classList.add('hidden');
                            }
                        });
                    </script>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#0A1A32] text-white px-6 py-2.5 rounded-xl shadow hover:shadow-lg hover:bg-[#152a4d] font-bold transition-all flex items-center">
                    <i class="fas fa-cloud-upload-alt mr-2"></i> Upload Document
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
