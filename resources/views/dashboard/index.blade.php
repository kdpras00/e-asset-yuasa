@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Overview</h1>
    <p class="text-gray-500 mt-1">Asset performance and distribution</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Assets</p>
                <h2 class="text-4xl font-extrabold text-gray-900">{{ $summary['total_assets'] ?? 21 }}</h2>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                <i class="fas fa-boxes"></i>
            </div>
        </div>
        <div class="mt-6 flex items-center">
            <span class="px-2 py-1 rounded-md bg-green-100 text-green-700 text-xs font-bold mr-2"><i class="fas fa-arrow-up mr-1"></i>100%</span>
            <span class="text-xs text-gray-400 font-medium">vs last month</span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Categories</p>
                <h2 class="text-4xl font-extrabold text-gray-900">{{ $summary['total_categories'] ?? 4 }}</h2>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-500">
                <i class="fas fa-tags"></i>
            </div>
        </div>
        <div class="mt-6 flex items-center">
            <span class="text-xs text-gray-400 font-medium">Active asset types</span>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="w-full mb-8">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 w-full">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-lg font-bold text-gray-900">Asset Distribution</h3>
            <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-h"></i></button>
        </div>
        <div class="flex flex-col md:flex-row items-center justify-center gap-12 relative">
            <div class="relative w-64 h-64">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="flex flex-col justify-center space-y-4">
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-[#1a1c23] mr-3"></span>
                    <span class="text-sm text-gray-500 font-medium">Peralatan Kerja</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-blue-500 mr-3"></span>
                    <span class="text-sm text-gray-500 font-medium">Peralatan Elektronik</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-purple-500 mr-3"></span>
                    <span class="text-sm text-gray-500 font-medium">Peralatan Pendukung</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full bg-pink-500 mr-3"></span>
                    <span class="text-sm text-gray-500 font-medium">Aset Penunjang Lainnya</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Assets Table -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Recent Assets</h3>
        <button class="text-sm text-blue-600 font-medium hover:text-blue-700">View All</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <th class="px-6 py-4 font-bold">Asset Name</th>
                    <th class="px-6 py-4 font-bold">Category</th>
                    <th class="px-6 py-4 font-bold">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Date Added</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500"><i class="fas fa-laptop"></i></div>
                            <div>
                                <p class="font-bold text-gray-900">MacBook Pro M2</p>
                                <p class="text-[11px] text-gray-500">IT-LAP-001</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-medium">Peralatan Elektronik</td>
                    <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-md bg-green-100 text-green-700 text-xs font-bold">Active</span></td>
                    <td class="px-6 py-4 text-right text-gray-500 font-medium">Today, 09:41</td>
                </tr>
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500"><i class="fas fa-print"></i></div>
                            <div>
                                <p class="font-bold text-gray-900">Epson L3110 Printer</p>
                                <p class="text-[11px] text-gray-500">PR-EPS-042</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-medium">Peralatan Kerja</td>
                    <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-md bg-green-100 text-green-700 text-xs font-bold">Active</span></td>
                    <td class="px-6 py-4 text-right text-gray-500 font-medium">Yesterday</td>
                </tr>
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-pink-50 flex items-center justify-center text-pink-500"><i class="fas fa-chair"></i></div>
                            <div>
                                <p class="font-bold text-gray-900">Ergonomic Chair</p>
                                <p class="text-[11px] text-gray-500">FUR-CH-012</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-medium">Peralatan Pendukung</td>
                    <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-md bg-yellow-100 text-yellow-700 text-xs font-bold">Maintenance</span></td>
                    <td class="px-6 py-4 text-right text-gray-500 font-medium">24 Jun 2026</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#9ca3af';

    const ctx = document.getElementById('categoryChart').getContext('2d');
    
    // Gradient for segment 1
    const grad1 = ctx.createLinearGradient(0, 0, 0, 400);
    grad1.addColorStop(0, '#1a1c23');
    grad1.addColorStop(1, '#374151');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Peralatan Kerja', 'Peralatan Elektronik', 'Peralatan Pendukung', 'Aset Penunjang Lainnya'],
            datasets: [{
                data: [35, 25, 20, 20], // Example data matching the screenshot
                backgroundColor: [
                    '#1a1c23', // Dark
                    '#3b82f6', // Blue
                    '#8b5cf6', // Purple
                    '#ec4899'  // Pink
                ],
                borderWidth: 0,
                cutout: '70%',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Hide default legend as we use custom HTML legend
                },
                tooltip: {
                    backgroundColor: '#030405',
                    titleFont: { size: 13, family: "'Poppins', sans-serif" },
                    bodyFont: { size: 13, family: "'Poppins', sans-serif" },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
</script>
@endsection
