@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">My Loans</h1>
            <p class="text-gray-500 text-sm mt-1">Assets currently assigned to you.</p>
        </div>
        <a href="{{ route('loans.create') }}" class="bg-[#0A1A32] text-white px-5 py-2.5 rounded-xl shadow hover:bg-[#152a4d] transition-all font-medium flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajukan Peminjaman
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Asset Details</th>
                        <th class="px-6 py-4">Borrow Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Notes</th>
                        <th class="px-6 py-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                             <div class="flex items-center gap-3">
                                @if($loan->asset->image)
                                <img src="{{ Storage::url($loan->asset->image) }}" class="w-12 h-12 rounded-lg object-cover border border-gray-100">
                                @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-box"></i>
                                </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-800">{{ $loan->asset->name }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $loan->asset->code }}</p>
                                    <span class="text-[10px] bg-gray-100 px-1.5 py-0.5 rounded text-gray-500 mt-1 inline-block">{{ $loan->asset->category }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <i class="far fa-calendar-alt mr-1 text-gray-400"></i>
                            {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                            @if($loan->return_date)
                                <div class="text-xs text-green-600 mt-1">Returned: {{ \Carbon\Carbon::parse($loan->return_date)->format('d M') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($loan->status === 'borrowed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-1.5"></span>
                                    Borrowed
                                </span>
                            @elseif($loan->status === 'pending_return')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                    <i class="fas fa-clock mr-1"></i> Waiting Return Approval
                                </span>
                            @elseif($loan->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Waiting Approval
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                    Returned
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs italic text-gray-500 max-w-xs truncate">
                            {{ $loan->notes ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($loan->status === 'borrowed')
                            <form action="{{ route('loans.request_return', $loan->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-lg font-bold transition-colors">
                                    Kembalikan
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-check text-green-500 text-xl"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">No Active Loans</h3>
                            <p class="text-gray-400 text-sm mt-1">You don't have any assets currently borrowed.</p>
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
