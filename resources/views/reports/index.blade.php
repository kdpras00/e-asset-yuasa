@extends('layouts.app')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Documents</h1>
            <p class="text-gray-500 mt-1">Manage all asset-related documents and files.</p>
        </div>
        @if(Auth::user()->role != 'hrd')
        <a href="{{ route('documents.create') }}" class="bg-[#0A1A32] text-white px-6 py-2.5 rounded-xl shadow-lg hover:shadow-xl hover:bg-[#152a4d] font-bold transition-all flex items-center">
            <i class="fas fa-plus mr-2"></i> Upload New Document
        </a>
        @endif
    </div>

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Asset
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Tipe Dokumen
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Tanggal Upload
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p class="text-gray-900 whitespace-no-wrap font-bold">
                                    {{ $doc->asset->name }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap text-xs">
                                    {{ $doc->asset->code }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap capitalize">{{ $doc->type }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight 
                        {{ $doc->status == 'approved' ? 'text-green-900' : ($doc->status == 'rejected' ? 'text-red-900' : 'text-yellow-900') }}">
                            <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full
                            {{ $doc->status == 'approved' ? 'bg-green-200' : ($doc->status == 'rejected' ? 'bg-red-200' : 'bg-yellow-200') }}">
                            </span>
                            <span class="relative">{{ ucfirst($doc->status) }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $doc->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="{{ route('documents.show', $doc->id) }}" class="text-blue-600 hover:text-blue-900 mr-3 block mb-1 font-bold">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        
                        @if($doc->status == 'pending' && trim(Auth::user()->role) == 'pimpinan')
                            <div class="flex mt-2">
                                <form action="{{ route('documents.approve', $doc->id) }}" method="POST" class="mr-2">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-1 px-2 rounded">
                                        ACC
                                    </button>
                                </form>
                                <form action="{{ route('documents.reject', $doc->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
