@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Peminjaman Aset</h1>
            <p class="text-gray-500 text-sm mt-1">Catat peminjaman aset baru ke karyawan.</p>
        </div>
        <a href="{{ route('loans.index') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Batal
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf
            
            <div class="p-8 space-y-6">
                <!-- Asset Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Aset <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="asset_id" id="asset_id" required onchange="updateStockLimit(this)" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            <option value="" disabled selected>Pilih aset...</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" 
                                    data-stock="{{ $asset->stock }}" 
                                    {{ $asset->stock <= 0 ? 'disabled' : '' }} 
                                    class="{{ $asset->stock <= 0 ? 'text-gray-400 bg-gray-50' : '' }}">
                                    {{ $asset->name }} ({{ $asset->code }}) - Stock: {{ $asset->stock }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Employee Selection -->
                <div>
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Karyawan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="user_id" id="user_id" required onchange="updateUserInfo(this)" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            <option value="" disabled selected>Pilih karyawan...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" data-image="{{ $user->image ? Storage::url($user->image) : '' }}" data-position="{{ $user->position ?? '-' }}" data-name="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Selected User Info Preview -->
                    <div id="userInfo" class="mt-4 hidden p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-center gap-4 animate-fade-in-down">
                        <div class="h-16 w-16 rounded-full bg-white border-2 border-white shadow-sm overflow-hidden flex-shrink-0">
                            <img id="userImage" src="" alt="User" class="w-full h-full object-cover hidden">
                            <div id="userInitials" class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-xl">U</div>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800" id="userName">User Name</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mt-0.5" id="userPosition">Position</p>
                        </div>
                    </div>
                </div>
                
                <!-- Amount -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Pinjam <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="amount" value="1" min="1" required oninput="validateStock()" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                    <p class="text-xs text-gray-400 mt-1" id="stockHint">Tentukan jumlah stok yang akan dipinjam.</p>
                    <p class="text-xs text-red-500 mt-1 hidden font-bold animate-pulse" id="stockError">
                        <i class="fas fa-exclamation-circle mr-1"></i> Jumlah yang diminta melebihi stok yang tersedia!
                    </p>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pinjam <span class="text-red-500">*</span></label>
                    <input type="date" name="loan_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700" placeholder="Catatan opsional..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#0A1A32] text-white px-6 py-2.5 rounded-xl shadow hover:shadow-lg hover:bg-[#152a4d] font-bold transition-all flex items-center">
                    <i class="fas fa-check mr-2"></i> Konfirmasi Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>
@section('scripts')
<script>
    let currentStock = 0;

    function updateStockLimit(select) {
        const option = select.options[select.selectedIndex];
        currentStock = parseInt(option.getAttribute('data-stock')) || 0;
        
        const amountInput = document.getElementById('amount');
        const stockHint = document.getElementById('stockHint');
        
        amountInput.max = currentStock;
        stockHint.textContent = `Stok Tersedia: ${currentStock} unit`;
        
        validateStock();
    }

    function validateStock() {
        const amountInput = document.getElementById('amount');
        const stockError = document.getElementById('stockError');
        const val = parseInt(amountInput.value) || 0;
        
        if (currentStock > 0 && val > currentStock) {
            stockError.classList.remove('hidden');
            amountInput.classList.add('border-red-500', 'text-red-600', 'focus:border-red-500', 'focus:ring-red-100');
            amountInput.classList.remove('border-gray-300', 'focus:ring-blue-100', 'focus:border-blue-500');
        } else {
            stockError.classList.add('hidden');
            amountInput.classList.remove('border-red-500', 'text-red-600', 'focus:border-red-500', 'focus:ring-red-100');
            amountInput.classList.add('border-gray-300', 'focus:ring-blue-100', 'focus:border-blue-500');
        }
    }

    function updateUserInfo(select) {
        const option = select.options[select.selectedIndex];
        const image = option.getAttribute('data-image');
        const position = option.getAttribute('data-position');
        const name = option.getAttribute('data-name');
        
        const userInfo = document.getElementById('userInfo');
        const userImage = document.getElementById('userImage');
        const userInitials = document.getElementById('userInitials');
        const userName = document.getElementById('userName');
        const userPosition = document.getElementById('userPosition');
        
        if (select.value) {
            userInfo.classList.remove('hidden');
            userName.textContent = name;
            userPosition.textContent = position;
            
            if (image) {
                userImage.src = image;
                userImage.classList.remove('hidden');
                userInitials.classList.add('hidden');
            } else {
                userImage.classList.add('hidden');
                userInitials.classList.remove('hidden');
                userInitials.textContent = name.charAt(0);
            }
        } else {
            userInfo.classList.add('hidden');
        }
    }
</script>
@endsection
@endsection
