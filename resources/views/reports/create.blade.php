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
                    @error('asset_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Document Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Document Type <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="type" id="type" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            <option value="" disabled selected>Select document type...</option>
                            <option value="pembelian">Pembelian (Purchase)</option>
                            <option value="pemeliharaan">Pemeliharaan (Maintenance)</option>
                            <option value="pemusnahan">Pemusnahan (Disposal)</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 pointer-events-none"></i>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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
                            <!-- Filename/Image preview -->
                            <div id="preview-container" class="hidden absolute inset-0 bg-white flex flex-col items-center justify-center text-blue-700 font-medium p-4 text-center z-10 w-full h-full">
                                <img id="img-preview" class="hidden max-h-32 object-contain mb-2 rounded shadow-sm">
                                <p id="file-name" class="text-sm truncate w-full px-4"></p>
                                <button type="button" id="remove-file" class="mt-2 text-xs text-red-500 hover:text-red-700 font-bold underline z-20">Remove</button>
                            </div>
                            <input id="dropzone-file" name="document" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-0" accept=".pdf,.jpg,.jpeg,.png" required />
                        </label>
                    </div>
                    @error('document')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <script>
                        const dropzoneInput = document.getElementById('dropzone-file');
                        const previewContainer = document.getElementById('preview-container');
                        const imgPreview = document.getElementById('img-preview');
                        const fileNameDisplay = document.getElementById('file-name');
                        const placeholder = document.getElementById('upload-placeholder');
                        const removeBtn = document.getElementById('remove-file');

                        dropzoneInput.addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if(file) {
                                fileNameDisplay.textContent = file.name;
                                previewContainer.classList.remove('hidden');
                                placeholder.classList.add('hidden'); // Hide the upload prompts

                                if (file.type.match('image.*')) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        imgPreview.src = e.target.result;
                                        imgPreview.classList.remove('hidden');
                                    }
                                    reader.readAsDataURL(file);
                                } else {
                                    imgPreview.classList.add('hidden');
                                    imgPreview.src = '';
                                }
                            }
                        });

                        removeBtn.addEventListener('click', function(e) {
                            e.preventDefault(); // Prevent label click
                            e.stopPropagation(); // Stop bubble
                            dropzoneInput.value = '';
                            previewContainer.classList.add('hidden');
                            placeholder.classList.remove('hidden');
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
