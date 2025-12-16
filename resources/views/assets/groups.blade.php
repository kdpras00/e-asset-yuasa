@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Asset Groups</h1>
        <p class="text-gray-500 mt-1">Categorized groups of system assets.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($groups as $group)
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group cursor-pointer hover:-translate-y-1">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-red-50 rounded-xl group-hover:bg-[#9E3E3E] transition-colors group-hover:text-white text-[#9E3E3E]">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $group->count }} Assets</span>
        </div>
        
        <h3 class="text-lg font-bold text-gray-800 mb-1 line-clamp-1">{{ $group->group ?? 'Uncategorized' }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ $group->category }}</p>

        <div class="border-t pt-4 flex justify-between items-center text-sm">
             <span class="text-gray-400">Updated: {{ \Carbon\Carbon::parse($group->last_update)->diffForHumans() }}</span>
             <a href="{{ route('assets.index', ['group' => $group->group]) }}" class="text-blue-600 hover:text-blue-800 font-semibold">View List &rarr;</a>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-500">
        No groups found.
    </div>
    @endforelse
</div>
@endsection
