@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Kategori Asset</h1>
        <p class="text-gray-500 mt-1">Asset classifications and statistics.</p>
    </div>
</div>

<div class="bg-white shadow-xl rounded-[20px] overflow-hidden border border-gray-100">
     <table class="min-w-full leading-normal">
        <thead>
            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                <th class="px-8 py-5 text-left text-xs font-bold uppercase tracking-wider">Category Name</th>
                <th class="px-8 py-5 text-left text-xs font-bold uppercase tracking-wider">Total Assets</th>
                <th class="px-8 py-5 text-left text-xs font-bold uppercase tracking-wider">Market Value (Est.)</th>
                <th class="px-8 py-5 text-left text-xs font-bold uppercase tracking-wider">Last Activity</th>
                <th class="px-8 py-5 text-right text-xs font-bold uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
             @forelse($categories as $cat)
             <tr class="hover:bg-gray-50 transition-colors">
                 <td class="px-8 py-5">
                     <div class="flex items-center">
                         <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-4">
                             {{ substr($cat->category, 0, 1) }}
                         </div>
                         <span class="font-bold text-gray-800">{{ $cat->category }}</span>
                     </div>
                 </td>
                 <td class="px-8 py-5">
                      <div class="flex items-center">
                          <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 max-w-[100px]">
                              <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ min($cat->count * 5, 100) }}%"></div>
                          </div>
                          <span class="text-sm text-gray-600 font-medium">{{ $cat->count }}</span>
                      </div>
                 </td>
                 <td class="px-8 py-5 text-sm text-gray-500 font-mono">
                     Rp {{ number_format($cat->total_price, 0, ',', '.') }}
                 </td>
                 <td class="px-8 py-5 text-sm text-gray-500">
                     {{ \Carbon\Carbon::parse($cat->last_update)->format('d M Y') }}
                 </td>
                 <td class="px-8 py-5 text-right">
                      <a href="{{ route('assets.index', ['search' => $cat->category]) }}" class="text-indigo-600 hover:text-indigo-900 border border-indigo-200 hover:bg-indigo-50 px-3 py-1 rounded-lg text-xs font-bold transition-all">
                          View Assets
                      </a>
                 </td>
             </tr>
             @empty
             <tr>
                 <td colspan="5" class="px-8 py-8 text-center text-gray-500">No categories found.</td>
             </tr>
             @endforelse
        </tbody>
     </table>
</div>
@endsection
