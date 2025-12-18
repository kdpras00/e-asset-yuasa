@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('reports.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Document Detail</h1>
            </div>
            <p class="text-gray-500 text-sm ml-7">Viewing document ID: #{{ $document->id }}</p>
        </div>
        
        <div class="flex gap-3">
             <a href="{{ Storage::url($document->file_path) }}" download class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-200 font-bold transition-all flex items-center border border-gray-200">
                <i class="fas fa-download mr-2"></i> Download
            </a>
            
             @if($document->status == 'pending' && Auth::user()->role == 'pimpinan')
            <form action="{{ route('documents.approve', $document->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl shadow font-bold transition-all flex items-center">
                    <i class="fas fa-check mr-2"></i> Approve
                </button>
            </form>
            <form action="{{ route('documents.reject', $document->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl shadow font-bold transition-all flex items-center">
                    <i class="fas fa-times mr-2"></i> Reject
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Asset Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Related Asset</h3>
                <div class="flex items-start gap-4">
                    @if($document->asset->image)
                        <img src="{{ Storage::url($document->asset->image) }}" class="w-16 h-16 rounded-xl object-cover border border-gray-100">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-box text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="font-bold text-gray-800 line-clamp-1">{{ $document->asset->name }}</h4>
                        <p class="text-xs text-gray-500 font-mono mb-2">{{ $document->asset->code }}</p>
                        <a href="{{ route('assets.show', $document->asset->id) }}" class="text-blue-600 text-xs font-bold hover:underline">View Asset &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Meta Data -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Document Info</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400">Document Type</p>
                        <p class="font-semibold text-gray-800 capitalize">{{ $document->type }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Upload Date</p>
                        <p class="font-semibold text-gray-800">{{ $document->created_at->format('d F Y, H:i') }}</p>
                    </div>
                     <div>
                        <p class="text-xs text-gray-400">Current Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold mt-1
                            {{ $document->status == 'approved' ? 'bg-green-100 text-green-800' : ($document->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($document->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Preview -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-[800px] flex flex-col">
                <div class="p-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700 text-sm">File Preview</h3>
                </div>
                
                <div class="flex-1 bg-gray-100 relative items-center justify-center flex overflow-hidden">
                    @php
                        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                    @endphp

                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <img src="{{ Storage::url($document->file_path) }}" class="max-w-full max-h-full object-contain">
                    @elseif(strtolower($extension) == 'pdf')
                        <iframe src="{{ Storage::url($document->file_path) }}" class="w-full h-full border-0"></iframe>
                    @else
                        <div class="text-center">
                            <i class="fas fa-file-alt text-6xl text-gray-300 mb-4 block"></i>
                            <p class="text-gray-500 font-medium">Preview not available for this file type.</p>
                            <a href="{{ Storage::url($document->file_path) }}" class="text-blue-600 text-sm font-bold mt-2 hover:underline">Download to view</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
