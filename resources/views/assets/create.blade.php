@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Register New Asset</h1>
            <p class="text-gray-500 text-sm mt-1">Fill in the details to add a new asset to the inventory.</p>
        </div>
        <a href="{{ route('assets.index') }}" class="bg-white text-gray-700 hover:text-gray-900 border border-gray-300 hover:bg-gray-50 font-medium text-sm flex items-center px-4 py-2 rounded-lg shadow-sm transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Cancel & Return
        </a>
    </div>

    <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Section 1: Basic Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                    <i class="fas fa-info"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Basic Information</h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Asset Type -->
                <div class="col-span-2 md:col-span-1">
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Asset Type <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="type" id="type" required class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            <option value="fixed" selected>Fixed Asset (Aset Tetap)</option>
                            <option value="consumable">Inventory/Consumable (Bhabis Pakai)</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Asset Name -->
                <div class="col-span-2 md:col-span-1">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Asset Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700" placeholder="e.g., MacBook Pro 2024">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="category" id="category" required class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            <option value="" disabled selected>Select Category</option>
                            <option value="Peralatan Kerja">Peralatan Kerja (Meja, Kursi, Lemari)</option>
                            <option value="Peralatan Elektronik">Peralatan Elektronik (PC, Laptop, Printer)</option>
                            <option value="Perlengkapan ATK">Perlengkapan ATK (Kertas, Pulpen)</option>
                            <option value="Peralatan Pendukung">Peralatan Pendukung (AC, Kipas)</option>
                            <option value="Aset Penunjang Lainnya">Aset Penunjang Lainnya (Kendaraan, ID Card)</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Asset Group (Location/Placement) -->
                <!-- Group Removed -->

                <!-- SAP Code -->
                <div>
                    <label for="sap_code" class="block text-sm font-semibold text-gray-700 mb-2">SAP Code</label>
                    <input type="text" name="sap_code" id="sap_code" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 font-mono" placeholder="e.g., SAP-100293">
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
                            <input type="radio" name="status" value="baik" class="peer sr-only" checked>
                            <div class="text-center py-2.5 rounded-xl border border-gray-200 text-gray-500 peer-checked:bg-green-50 peer-checked:border-green-200 peer-checked:text-green-700 transition-all font-medium">
                                <i class="fas fa-check-circle mr-1"></i> Good
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="rusak" class="peer sr-only">
                            <div class="text-center py-2.5 rounded-xl border border-gray-200 text-gray-500 peer-checked:bg-red-50 peer-checked:border-red-200 peer-checked:text-red-700 transition-all font-medium">
                                <i class="fas fa-times-circle mr-1"></i> Damaged
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Purchase Date -->
                <div>
                    <label for="purchase_date" class="block text-sm font-semibold text-gray-700 mb-2">Acquisition Date</label>
                    <input type="date" name="purchase_date" id="purchase_date" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                    <input type="text" name="department" id="department" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700" placeholder="e.g., HRD">
                </div>

                <!-- Section -->
                <div>
                    <label for="section" class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
                    <input type="text" name="section" id="section" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700" placeholder="e.g., Recruitment">
                </div>

                <!-- PIC -->
                <div class="col-span-2">
                    <label for="pic" class="block text-sm font-semibold text-gray-700 mb-2">Person In Charge (PIC)</label>
                    <input type="text" name="pic" id="pic" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700" placeholder="Name of employee responsible">
                </div>
                
                 <!-- Hidden Fields for Defaults -->
                <input type="hidden" name="code" value="{{ 'AST-' . strtoupper(Str::random(8)) }}">
                <!-- Quantity -->
                <div>
                     <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                     <input type="number" name="quantity" id="quantity" value="1" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                </div>
                
                 <!-- Hidden Fields for Defaults -->
                 <!-- Removed duplicate code hidden field -->
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Asset Image</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors group">
                            <div id="image-preview" class="hidden w-full h-full relative group/preview">
                                <img src="" alt="Preview" class="w-full h-full object-contain rounded-xl bg-gray-100">
                                
                                <!-- Hover Actions -->
                                <div class="absolute inset-0 bg-black/10 opacity-0 group-hover/preview:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-xl">
                                     <button type="button" onclick="document.getElementById('dropzone-file').click()" class="bg-white/90 text-gray-700 hover:text-blue-600 px-4 py-2 rounded-full font-bold shadow-sm text-sm transition-transform hover:scale-105">
                                        <i class="fas fa-sync-alt mr-2"></i> Change
                                     </button>
                                     <button type="button" onclick="const src = document.querySelector('#image-preview img').src; var w = window.open(''); w.document.write('<img src=\'' + src + '\' style=\'max-width:100%\'>');" class="bg-white/90 text-gray-700 hover:text-green-600 p-2 rounded-full font-bold shadow-sm transition-transform hover:scale-105" title="View Full">
                                        <i class="fas fa-expand"></i>
                                     </button>
                                </div>
                            </div>
                            <div id="upload-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 group-hover:text-blue-500 transition-colors mb-3"></i>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-400">SVG, PNG, JPG or GIF (MAX. 2MB)</p>
                            </div>
                            <input id="dropzone-file" name="image" type="file" class="hidden" accept="image/*" />
                            <script>
                                const dropzoneFile = document.getElementById('dropzone-file');
                                const imagePreview = document.getElementById('image-preview');
                                const uploadPlaceholder = document.getElementById('upload-placeholder');
                                const previewImg = imagePreview ? imagePreview.querySelector('img') : null;

                                if(dropzoneFile) {
                                    dropzoneFile.addEventListener('change', function(e) {
                                        const file = e.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                previewImg.src = e.target.result;
                                                imagePreview.classList.remove('hidden');
                                                uploadPlaceholder.classList.add('hidden');
                                            }
                                            reader.readAsDataURL(file);
                                        }
                                    });
                                }
                            </script>
                        </label>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Notes / Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700" placeholder="Additional details about the asset condition or specs..."></textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-[#0A1A32] text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl hover:bg-[#152a4d] font-bold transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-check-circle mr-2"></i> Save Asset
            </button>
        </div>
    </form>
</div>
@endsection
