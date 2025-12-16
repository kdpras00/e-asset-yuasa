@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Asset</h1>
            <p class="text-gray-500 text-sm mt-1">Update information for <span class="font-mono font-semibold">{{ $asset->sap_code ?? $asset->code }}</span></p>
        </div>
        <a href="{{ route('assets.show', $asset->id) }}" class="bg-white text-gray-700 hover:text-gray-900 border border-gray-300 hover:bg-gray-50 font-medium text-sm flex items-center px-4 py-2 rounded-lg shadow-sm transition-all">
            <i class="fas fa-times mr-2"></i> Cancel
        </a>
    </div>

    <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Basic Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                    <i class="fas fa-edit"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Basic Information</h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Asset Name -->
                <div class="col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Asset Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $asset->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="category" id="category" required class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            @foreach(['Electronics', 'Furniture', 'Vehicle', 'Machinery', 'IT Equipment'] as $cat)
                                <option value="{{ $cat }}" {{ $asset->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Group (Added) -->
                <div>
                     <label for="group" class="block text-sm font-semibold text-gray-700 mb-2">Group</label>
                     <input type="text" name="group" id="group" value="{{ old('group', $asset->group) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700" placeholder="e.g., Office A">
                </div>

                <!-- SAP Code -->
                <div>
                    <label for="sap_code" class="block text-sm font-semibold text-gray-700 mb-2">SAP Code</label>
                    <input type="text" name="sap_code" id="sap_code" value="{{ old('sap_code', $asset->sap_code) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 font-mono">
                </div>
            </div>
        </div>

        <!-- Section 2: Details & Allocation -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
             <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center">
                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-3">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Allocation & Status</h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Condition Status <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="baik" class="peer sr-only" {{ $asset->status == 'baik' ? 'checked' : '' }}>
                            <div class="text-center py-2.5 rounded-xl border border-gray-200 text-gray-500 peer-checked:bg-green-50 peer-checked:border-green-200 peer-checked:text-green-700 transition-all font-medium">
                                <i class="fas fa-check-circle mr-1"></i> Good
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="rusak" class="peer sr-only" {{ $asset->status == 'rusak' ? 'checked' : '' }}>
                            <div class="text-center py-2.5 rounded-xl border border-gray-200 text-gray-500 peer-checked:bg-red-50 peer-checked:border-red-200 peer-checked:text-red-700 transition-all font-medium">
                                <i class="fas fa-times-circle mr-1"></i> Damaged
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Purchase Date -->
                <div>
                    <label for="purchase_date" class="block text-sm font-semibold text-gray-700 mb-2">Acquisition Date</label>
                    <input type="date" name="purchase_date" id="purchase_date" value="{{ $asset->purchase_date }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                    <input type="text" name="department" id="department" value="{{ old('department', $asset->department) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                </div>

                <!-- Section -->
                <div>
                    <label for="section" class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
                    <input type="text" name="section" id="section" value="{{ old('section', $asset->section) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                </div>
                 
                 <!-- PIC -->
                <div class="col-span-2">
                    <label for="pic" class="block text-sm font-semibold text-gray-700 mb-2">Person In Charge (PIC)</label>
                    <input type="text" name="pic" id="pic" value="{{ old('pic', $asset->pic) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                </div>

                 <!-- Hidden Fields -->
                <input type="hidden" name="code" value="{{ $asset->code }}">
                <input type="hidden" name="quantity" value="{{ $asset->quantity }}">
            </div>
        </div>

        <!-- Section 3: Check Sheet & Image -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
             <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center">
                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 mr-3">
                    <i class="fas fa-camera"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Visuals & Proof</h2>
            </div>
            
            <div class="p-6">
                 <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Update Image</label>
                    <div class="flex gap-6 items-start">
                        @if($asset->image)
                            <div class="w-24 h-24 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0">
                                <img id="current-image-thumb" src="{{ Storage::url($asset->image) }}" alt="Current Image" class="w-full h-full object-cover">
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors group">
                                <div id="edit-image-preview" class="hidden w-full h-full relative group/preview">
                                    <img src="" alt="Preview" class="w-full h-full object-contain rounded-xl bg-gray-100">
                                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover/preview:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-xl">
                                         <button type="button" onclick="document.getElementById('dropzone-file').click()" class="bg-white/90 text-gray-700 hover:text-blue-600 px-4 py-2 rounded-full font-bold shadow-sm text-sm transition-transform hover:scale-105">
                                            <i class="fas fa-sync-alt mr-2"></i> Change
                                         </button>
                                         <button type="button" onclick="const src = document.querySelector('#edit-image-preview img').src; var w = window.open(''); w.document.write('<img src=\'' + src + '\' style=\'max-width:100%\'>');" class="bg-white/90 text-gray-700 hover:text-green-600 p-2 rounded-full font-bold shadow-sm transition-transform hover:scale-105" title="View Full">
                                            <i class="fas fa-expand"></i>
                                         </button>
                                    </div>
                                </div>
                                <div id="edit-upload-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Click to upload new</span></p>
                                    <p class="text-xs text-gray-400">MAX. 2MB</p>
                                </div>
                                <input id="dropzone-file" name="image" type="file" class="hidden" accept="image/*" />
                                <script>
                                    const editDropzone = document.getElementById('dropzone-file');
                                    const editPreview = document.getElementById('edit-image-preview');
                                    const editPlaceholder = document.getElementById('edit-upload-placeholder');
                                    const editImg = editPreview ? editPreview.querySelector('img') : null;
                                    const currentThumb = document.getElementById('current-image-thumb');

                                    if(editDropzone) {
                                        editDropzone.addEventListener('change', function(e) {
                                            const file = e.target.files[0];
                                            if (file) {
                                                const reader = new FileReader();
                                                reader.onload = function(e) {
                                                    // Update dropzone preview
                                                    editImg.src = e.target.result;
                                                    editPreview.classList.remove('hidden');
                                                    editPlaceholder.classList.add('hidden');
                                                    
                                                    // Update side thumbnail if exists
                                                    if(currentThumb) {
                                                        currentThumb.src = e.target.result;
                                                    }
                                                }
                                                reader.readAsDataURL(file);
                                            }
                                        });
                                    }
                                </script>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Notes / Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">{{ old('description', $asset->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-[#0A1A32] text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl hover:bg-[#152a4d] font-bold transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-save mr-2"></i> Update Changes
            </button>
        </div>
    </form>
</div>
@endsection
