@extends('dashboard.layout')

@section('title', 'Beranda Dashboard')

@section('content')
<div class="space-y-8">
    
    <!-- Top Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-white md:text-3xl">Pusat Kendali K3</h2>
            <p class="text-slate-400 text-sm mt-1">Pemantauan real-time mitigasi kebocoran gas Amonia & Klorin.</p>
        </div>
        <div class="text-xs text-slate-500 font-semibold px-4 py-2.5 bg-slate-900/40 border border-slate-800 rounded-xl flex items-center gap-2 self-start md:self-auto">
            <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
            <span>Server Terkoneksi: MySQL Aktif</span>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <!-- Total Mahasiswa Card -->
        <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/80 p-5 rounded-2xl flex flex-col justify-between shadow-lg hover:border-teal-500/30 hover:shadow-teal-500/5 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs text-slate-400 font-semibold tracking-wider uppercase">Mahasiswa</span>
                <div class="p-2.5 bg-teal-500/10 rounded-xl text-teal-400 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">{{ $totalMahasiswa }}</p>
                <p class="text-[11px] text-slate-500 font-medium mt-1">Total akun terdaftar</p>
            </div>
        </div>

        <!-- Rerata Pre-test Card -->
        <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/80 p-5 rounded-2xl flex flex-col justify-between shadow-lg hover:border-violet-500/30 hover:shadow-violet-500/5 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs text-slate-400 font-semibold tracking-wider uppercase">Skor Pre-Test</span>
                <div class="p-2.5 bg-violet-500/10 rounded-xl text-violet-400 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">{{ $avgPreTestScore }}<span class="text-sm font-semibold text-slate-500">/100</span></p>
                <p class="text-[11px] text-slate-500 font-medium mt-1">Rata-rata dari {{ $totalPreTest }} ujian</p>
            </div>
        </div>

        <!-- Survival Rate Card -->
        <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/80 p-5 rounded-2xl flex flex-col justify-between shadow-lg hover:border-emerald-500/30 hover:shadow-emerald-500/5 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs text-slate-400 font-semibold tracking-wider uppercase">Tingkat Survival</span>
                <div class="p-2.5 bg-emerald-500/10 rounded-xl text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">{{ $survivalRate }}<span class="text-sm font-semibold text-slate-500">%</span></p>
                <p class="text-[11px] text-slate-500 font-medium mt-1">Dari {{ $totalSimulation }} simulasi</p>
            </div>
        </div>

        <!-- Rerata Durasi Card -->
        <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/80 p-5 rounded-2xl flex flex-col justify-between shadow-lg hover:border-amber-500/30 hover:shadow-amber-500/5 transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs text-slate-400 font-semibold tracking-wider uppercase">Rerata Waktu</span>
                <div class="p-2.5 bg-amber-500/10 rounded-xl text-amber-400 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="timer" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">{{ $avgDuration }}<span class="text-sm font-semibold text-slate-500"> Detik</span></p>
                <p class="text-[11px] text-slate-500 font-medium mt-1">Hingga aman / fatal</p>
            </div>
        </div>

    </div>

    <!-- Charts Container -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Chart 1: Survival Rate -->
        <div class="bg-slate-900/40 backdrop-blur-xl border border-slate-800/80 p-5 md:p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-white md:text-lg">Survival Rate per Gas</h3>
                    <p class="text-xs text-slate-500">Tingkat keberhasilan menahan laju emisi gas virtual.</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold text-slate-400">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-teal-500 rounded-full"></span>Amonia</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>Klorin</span>
                </div>
            </div>
            <div class="relative h-[280px] w-full flex items-center justify-center">
                <canvas id="survivalChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Comparison -->
        <div class="bg-slate-900/40 backdrop-blur-xl border border-slate-800/80 p-5 md:p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-white md:text-lg">Durasi vs Paparan PPM Maksimal</h3>
                    <p class="text-xs text-slate-500">Korelasi rata-rata durasi penanganan dengan PPM puncak.</p>
                </div>
            </div>
            <div class="relative h-[280px] w-full flex items-center justify-center">
                <canvas id="comparisonChart"></canvas>
            </div>
        </div>

    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartData = @json($chartData);

        // Styling defaults for dark theme
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(51, 65, 85, 0.2)';
        Chart.defaults.font.family = 'Inter, sans-serif';

        // 1. Survival Rate Chart (Doughnut / Polar area / Bar)
        const ctx1 = document.getElementById('survivalChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Amonia (Kuning-Hijau)', 'Klorin (Emerald)'],
                datasets: [{
                    label: 'Tingkat Survival (%)',
                    data: [chartData.amonia_rate, chartData.klorin_rate],
                    backgroundColor: [
                        'rgba(20, 184, 166, 0.55)', // Teal
                        'rgba(16, 185, 129, 0.55)'  // Emerald
                    ],
                    borderColor: [
                        '#14b8a6', // Teal
                        '#10b981'  // Emerald
                    ],
                    borderWidth: 1.5,
                    borderRadius: 12,
                    barThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(20, 184, 166, 0.2)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return `Survival Rate: ${context.parsed.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(51, 65, 85, 0.15)'
                        },
                        ticks: {
                            callback: function(value) { return value + '%'; }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Comparison Chart (Radar or Multi-Bar)
        const ctx2 = document.getElementById('comparisonChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Amonia', 'Klorin'],
                datasets: [
                    {
                        label: 'Rerata Durasi (detik)',
                        data: [chartData.amonia_avg_duration, chartData.klorin_avg_duration],
                        backgroundColor: 'rgba(99, 102, 241, 0.5)', // Indigo
                        borderColor: '#6366f1',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        barPercentage: 0.6,
                        categoryPercentage: 0.6
                    },
                    {
                        label: 'Rerata PPM Maksimal',
                        data: [chartData.amonia_avg_ppm, chartData.klorin_avg_ppm],
                        backgroundColor: 'rgba(245, 158, 11, 0.5)', // Amber
                        borderColor: '#f59e0b',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        barPercentage: 0.6,
                        categoryPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(99, 102, 241, 0.2)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(51, 65, 85, 0.15)'
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
