@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Lend Asset</h1>
            <p class="text-gray-500 text-sm mt-1">Record a new asset loan to an employee.</p>
        </div>
        <a href="{{ route('loans.index') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Cancel
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf
            
            <div class="p-8 space-y-6">
                <!-- Asset Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Select Asset <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="asset_id" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            <option value="" disabled selected>Choose an asset...</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }} ({{ $asset->code }}) - {{ ucfirst($asset->status) }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Employee Selection -->
                @if(Auth::user()->role == 'karyawan')
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    <div>
                         <label class="block text-sm font-semibold text-gray-700 mb-2">Borrower</label>
                         <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500">
                             {{ Auth::user()->name }} (You)
                         </div>
                    </div>
                @else
                <div>
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Employee <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="user_id" id="user_id" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700 appearance-none bg-white">
                            <option value="" disabled selected>Choose an employee...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                @endif

                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Date <span class="text-red-500">*</span></label>
                    <input type="date" name="loan_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700">
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors text-gray-700" placeholder="Optional notes..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#0A1A32] text-white px-6 py-2.5 rounded-xl shadow hover:shadow-lg hover:bg-[#152a4d] font-bold transition-all flex items-center">
                    <i class="fas fa-check mr-2"></i> Confirm Loan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
