@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Dashboard Overview</h1>
        <p class="text-gray-500 mt-1">Monitor asset performance and distribution.</p>
    </div>
    <div class="flex gap-3">
         <button class="bg-white text-gray-600 px-4 py-2 rounded-lg shadow-sm font-medium hover:bg-gray-50 border transition-transform hover:-translate-y-0.5">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
        <button class="bg-[#0A1A32] text-white px-4 py-2 rounded-lg shadow-lg font-medium hover:bg-[#152a4d] transition-transform hover:-translate-y-0.5">
            <i class="fas fa-download mr-2"></i> Export Report
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fas fa-boxes text-6xl text-blue-900"></i>
        </div>
        <h3 class="text-gray-500 font-semibold uppercase tracking-wider text-xs mb-1">Total Asset List</h3>
        <p class="text-4xl font-extrabold text-[#0A1A32]">{{ $summary['total_assets'] ?? 0 }}</p>
        <div class="mt-4 flex items-center text-sm {{ $summary['month_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
            <i class="fas fa-arrow-{{ $summary['month_growth'] >= 0 ? 'up' : 'down' }} mr-1"></i> 
            <span class="font-bold">{{ abs($summary['month_growth']) }}%</span> 
            <span class="text-gray-400 ml-1">vs last month</span>
        </div>
    </div>

    <!-- Group Stat Removed -->

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fas fa-tags text-6xl text-purple-600"></i>
        </div>
        <h3 class="text-gray-500 font-semibold uppercase tracking-wider text-xs mb-1">Kategori</h3>
        <p class="text-4xl font-extrabold text-purple-700">{{ $summary['total_categories'] ?? 0 }}</p>
        <div class="mt-4 flex items-center text-sm text-gray-500">
             <span class="font-medium">Types of Assets</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Group Chart Removed -->
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
             <span class="w-1 h-6 bg-[#9E3E3E] rounded-full mr-3"></span>
            Assets by Category
        </h3>
        <div class="relative h-64">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Styles
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
    
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');

    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($charts['categories']['labels']) !!},
            datasets: [{
                data: {!! json_encode($charts['categories']['data']) !!},
                backgroundColor: ['#9E3E3E', '#F87171', '#FCA5A5', '#0A1A32', '#374151', '#9CA3AF'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
               legend: { position: 'right', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });
</script>
@endsection
