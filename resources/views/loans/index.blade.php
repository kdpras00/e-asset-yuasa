@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Loan Management</h1>
            <p class="text-gray-500 text-sm mt-1">Track and manage asset lending.</p>
        </div>
        <a href="{{ route('loans.create') }}" class="bg-[#0A1A32] text-white px-5 py-2.5 rounded-xl shadow hover:bg-[#152a4d] transition-all font-medium flex items-center">
            <i class="fas fa-plus mr-2"></i> Lend Asset
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Active Loans</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ App\Models\AssetLoan::where('status', 'borrowed')->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fas fa-hand-holding shadow-sm"></i>
            </div>
        </div>
        <!-- More stats could go here -->
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Borrower</th>
                        <th class="px-6 py-4">Asset</th>
                        <th class="px-6 py-4">Loan Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                    {{ substr($loan->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $loan->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $loan->user->role }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                             <div class="flex items-center gap-3">
                                @if($loan->asset->image)
                                <img src="{{ Storage::url($loan->asset->image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-box"></i>
                                </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $loan->asset->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $loan->asset->code }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                            @if($loan->return_date)
                                <br><span class="text-xs text-green-600">Returned: {{ \Carbon\Carbon::parse($loan->return_date)->format('d M') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $loan->status === 'borrowed' ? 'bg-orange-100 text-orange-800' : 
                                  ($loan->status === 'returned' ? 'bg-green-100 text-green-800' : 
                                  ($loan->status === 'pending_return' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('loans.show', $loan->id) }}" class="text-blue-600 hover:text-blue-900 block font-bold mb-2 text-xs">
                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                            </a>

                            <!-- Mark Returned moved to Detail View -->

                            <!-- Approval buttons moved to Detail View -->

                            @if($loan->status === 'pending')
                                <span class="text-xs text-gray-400 italic block mt-1">Pending Approval</span>
                            @elseif($loan->status === 'pending_return')
                                <span class="text-xs text-blue-500 font-bold block mt-1">Return Requested</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-box-open text-4xl mb-3 opacity-30"></i>
                            <p>No active loans found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $loans->links() }}
        </div>
    </div>
</div>
@endsection
