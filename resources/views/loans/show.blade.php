@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('loans.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Detail Peminjaman</h1>
            </div>
            <p class="text-gray-500 text-sm ml-7">ID Permintaan: #{{ $loan->id }}</p>
        </div>
        
        <!-- Action Buttons for Pimpinan -->
        @if(Auth::user()->role == 'pimpinan')
            @if($loan->status == 'pending')
            <div class="flex gap-3">
                 <form action="{{ route('loans.approve', $loan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl shadow font-bold transition-all flex items-center">
                        <i class="fas fa-check mr-2"></i> Setujui
                    </button>
                </form>
                <form action="{{ route('loans.reject', $loan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl shadow font-bold transition-all flex items-center">
                        <i class="fas fa-times mr-2"></i> Tolak
                    </button>
                </form>
            </div>
            @elseif($loan->status == 'pending_return')
            <div class="flex gap-3">
                 <form action="{{ route('loans.approve', $loan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow font-bold transition-all flex items-center">
                        <i class="fas fa-check-double mr-2"></i> Konfirmasi Pengembalian
                    </button>
                </form>
                <form action="{{ route('loans.reject', $loan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl shadow font-bold transition-all flex items-center">
                        <i class="fas fa-times mr-2"></i> Tolak Pengembalian
                    </button>
                </form>
            </div>
            @endif
        @endif

        <!-- Mark Returned Action (Available to Tim Asset ONLY) -->
        @if($loan->status == 'borrowed' && Auth::user()->role == 'tim_faxed_asset')
             <form action="{{ route('loans.return', $loan->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow font-bold transition-all flex items-center">
                    <i class="fas fa-undo mr-2"></i> Tandai Kembali
                </button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Asset Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6 pb-2 border-b border-gray-50">Info Aset</h3>
            
            <div class="flex items-start gap-4 mb-6">
                 @if($loan->asset->image)
                    <img src="{{ Storage::url($loan->asset->image) }}" class="w-24 h-24 rounded-2xl object-cover border border-gray-100 shadow-sm">
                @else
                    <div class="w-24 h-24 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400">
                        <i class="fas fa-box text-3xl"></i>
                    </div>
                @endif
                <div>
                    <h4 class="text-xl font-bold text-gray-800 leading-tight mb-1">{{ $loan->asset->name }}</h4>
                    <p class="text-sm text-gray-500 font-mono mb-2">{{ $loan->asset->code }}</p>
                    <span class="inline-block bg-gray-100 text-gray-600 text-[10px] uppercase font-bold px-2 py-1 rounded tracking-wide">{{ $loan->asset->category }}</span>
                </div>
            </div>
            
            <div class="space-y-4">
                 <div>
                    <p class="text-xs text-gray-400">Lokasi</p>
                    <p class="font-semibold text-gray-800">{{ $loan->asset->group }}</p>
                </div>
                 <div>
                    <p class="text-xs text-gray-400">Kondisi Saat Ini</p>
                    <p class="font-semibold text-gray-800 capitalize">{{ $loan->asset->status }}</p>
                </div>
            </div>
        </div>

        <!-- Loan Information -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6 pb-2 border-b border-gray-50">Info Peminjam</h3>
                <div class="flex items-center gap-4">
                     <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xl">
                        {{ substr($loan->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-lg">{{ $loan->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $loan->user->email }}</p>
                        <p class="text-xs text-gray-400 mt-1 uppercase tracking-wide">{{ $loan->user->role }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                 <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6 pb-2 border-b border-gray-50">Detail Permintaan</h3>
                 
                 <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400">Tanggal Permintaan</p>
                        <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold mt-1
                            {{ $loan->status === 'borrowed' ? 'bg-orange-100 text-orange-800' : 
                              ($loan->status === 'returned' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Jumlah Pinjam</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $loan->amount ?? 1 }} Unit</p>
                    </div>
                    @if($loan->notes)
                    <div>
                        <p class="text-xs text-gray-400">Catatan</p>
                        <p class="font-medium text-gray-700 mt-1 italic">"{{ $loan->notes }}"</p>
                    </div>
                    @endif
                 </div>
            </div>

            <!-- Timeline Activity -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6 pb-2 border-b border-gray-50">Aktivitas Timeline</h3>
                
                <div class="relative border-l-2 border-gray-100 ml-3 space-y-8">
                    @forelse($loan->activities as $activity)
                    <div class="relative pl-8">
                        <!-- Icon -->
                        <div class="absolute -left-[9px] top-0 w-5 h-5 rounded-full border-2 border-white 
                            {{ $activity->action == 'created' ? 'bg-blue-500' : 
                              ($activity->action == 'approved' ? 'bg-green-500' : 
                              ($activity->action == 'rejected' ? 'bg-red-500' : 
                              ($activity->action == 'returned' ? 'bg-teal-500' : 'bg-gray-400'))) }}">
                        </div>
                        
                        <div>
                            <p class="text-sm font-bold text-gray-800 capitalize">
                                {{ str_replace('_', ' ', $activity->action) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                oleh <span class="font-semibold text-gray-700">{{ $activity->user->name ?? 'Unknown' }}</span> • {{ $activity->created_at->format('d M H:i') }}
                            </p>
                            @if($activity->description)
                            <p class="text-xs text-gray-400 mt-1 italic">
                                "{{ $activity->description }}"
                            </p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 pl-4 italic">Belum ada aktivitas tercatat.</p>
                    @endforelse
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
