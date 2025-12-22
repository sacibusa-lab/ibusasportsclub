@extends('admin.layout')

@section('title', 'Web Analytics')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-black italic uppercase tracking-tighter text-secondary drop-shadow-md">
            Traffic Analytics
        </h2>
        <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest bg-white px-4 py-2 rounded-xl border border-zinc-100">
            Last 30 Days
        </span>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-secondary/10 rounded-full blur-2xl group-hover:bg-secondary/20 transition"></div>
            <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Total Page Views</h3>
            <p class="text-4xl font-black text-primary tracking-tighter">{{ number_format($totalViews) }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-secondary/10 rounded-full blur-2xl group-hover:bg-secondary/20 transition"></div>
            <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Unique Visitors</h3>
            <p class="text-4xl font-black text-primary tracking-tighter">{{ number_format($uniqueVisitors) }}</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm relative overflow-hidden group">
            <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Mobile Traffic</h3>
            <div class="flex items-end gap-2">
                <p class="text-2xl font-black text-primary tracking-tighter">{{ number_format($mobileVisits) }}</p>
                <span class="text-[10px] font-bold text-zinc-400 mb-1">
                    ({{ $totalViews > 0 ? round(($mobileVisits / $totalViews) * 100) : 0 }}%)
                </span>
            </div>
            <div class="w-full bg-zinc-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="h-full bg-secondary" style="width: {{ $totalViews > 0 ? ($mobileVisits / $totalViews) * 100 : 0 }}%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm relative overflow-hidden group">
            <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Desktop Traffic</h3>
            <div class="flex items-end gap-2">
                <p class="text-2xl font-black text-primary tracking-tighter">{{ number_format($desktopVisits) }}</p>
                <span class="text-[10px] font-bold text-zinc-400 mb-1">
                    ({{ $totalViews > 0 ? round(($desktopVisits / $totalViews) * 100) : 0 }}%)
                </span>
            </div>
            <div class="w-full bg-zinc-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="h-full bg-primary" style="width: {{ $totalViews > 0 ? ($desktopVisits / $totalViews) * 100 : 0 }}%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-zinc-100 shadow-sm">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6">Traffic Trends</h3>
            <div class="relative h-64 w-full">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>

        <!-- Top Pages -->
        <div class="bg-white p-8 rounded-3xl border border-zinc-100 shadow-sm">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6">Top Pages</h3>
            <div class="space-y-4">
                @foreach($topPages as $page)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-6 h-6 rounded-lg bg-zinc-50 flex items-center justify-center text-zinc-300 font-bold text-[10px]">
                            {{ $loop->iteration }}
                        </div>
                        <span class="text-xs font-bold text-zinc-600 truncate group-hover:text-primary transition">
                            /{{ ltrim($page->url, '/') }}
                        </span>
                    </div>
                    <span class="text-xs font-black text-primary tabular-nums">{{ number_format($page->views) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('trafficChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Visitors',
                data: @json($data),
                borderColor: '{{ $siteSettings['secondary_color'] }}',
                backgroundColor: 'rgba(0,0,0,0)', // Transparent
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '{{ $siteSettings['secondary_color'] }}',
                pointBorderWidth: 3,
                pointRadius: 4,
                active: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleFont: { family: 'Inter', size: 10 },
                    bodyFont: { family: 'Inter', size: 12, weight: 'bold' },
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6', borderDash: [5, 5] },
                    ticks: { font: { family: 'Inter', size: 10 }, color: '#9ca3af' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter', size: 10 }, color: '#9ca3af', maxTicksLimit: 7 }
                }
            }
        }
    });
</script>
@endpush
@endsection
