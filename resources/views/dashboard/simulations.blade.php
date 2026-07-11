@extends('dashboard.layout')

@section('title', 'Log Simulasi AR')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-white md:text-3xl">Log Simulasi WebAR K3</h2>
            <p class="text-slate-400 text-sm mt-1">Rekaman aktivitas mitigasi kebocoran bahan kimia berbahaya.</p>
        </div>
        <!-- Export Button -->
        <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-600 hover:to-emerald-600 text-slate-950 font-bold rounded-xl shadow-lg shadow-teal-500/10 hover:shadow-teal-500/20 active:scale-95 transition-all duration-300 self-start sm:self-auto cursor-pointer">
            <i data-lucide="download" class="w-4.5 h-4.5"></i>
            <span>Ekspor Data CSV</span>
        </a>
    </div>

    <!-- Filters Container (Mobile-first wrapping) -->
    <div class="bg-slate-900/40 backdrop-blur-xl border border-slate-800/80 p-4.5 rounded-2xl shadow-lg">
        <form action="{{ route('dashboard.simulations') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            
            <!-- Search field -->
            <div>
                <label for="search" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pencarian</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-550 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="block w-full pl-9 pr-3 py-2 bg-slate-950/40 border border-slate-850 rounded-xl text-xs text-slate-200 placeholder-slate-650 focus:outline-none focus:border-teal-500/60 focus:ring-1 focus:ring-teal-500/60 transition-all"
                           placeholder="Nama / Email Mahasiswa...">
                </div>
            </div>

            <!-- Gas Type filter -->
            <div>
                <label for="gas_type" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Gas</label>
                <select name="gas_type" id="gas_type" onchange="this.form.submit()"
                        class="block w-full px-3 py-2 bg-slate-950/40 border border-slate-850 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-teal-500/60 focus:ring-1 focus:ring-teal-500/60 transition-all cursor-pointer">
                    <option value="">Semua Gas</option>
                    <option value="amonia" {{ request('gas_type') === 'amonia' ? 'selected' : '' }}>Amonia (Kuning Kehijauan)</option>
                    <option value="klorin" {{ request('gas_type') === 'klorin' ? 'selected' : '' }}>Klorin (Hijau Pekat)</option>
                </select>
            </div>

            <!-- Status filter -->
            <div>
                <label for="status" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status Akhir</label>
                <select name="status" id="status" onchange="this.form.submit()"
                        class="block w-full px-3 py-2 bg-slate-950/40 border border-slate-850 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-teal-500/60 focus:ring-1 focus:ring-teal-500/60 transition-all cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="survived" {{ request('status') === 'survived' ? 'selected' : '' }}>Survived (Berhasil)</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed (Gagal)</option>
                </select>
            </div>

            <!-- Reset Button / Submit wrapper -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-grow py-2 px-4 bg-slate-800 hover:bg-slate-750 text-slate-200 border border-slate-700/80 rounded-xl text-xs font-semibold tracking-wide transition-all duration-300 cursor-pointer">
                    Filter Data
                </button>
                @if(request()->anyFilled(['search', 'gas_type', 'status']))
                    <a href="{{ route('dashboard.simulations') }}" 
                       class="p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-450 border border-rose-500/20 rounded-xl text-xs font-semibold transition-all duration-300 shrink-0" 
                       title="Reset Filters">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- MOBILE CARD LAYOUT (Visible only on mobile screens < 640px) -->
    <div class="block sm:hidden space-y-4">
        @forelse($simulations as $sim)
            <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/80 p-5 rounded-2xl space-y-4 shadow-lg relative overflow-hidden">
                <!-- Gas Type Indicator Accent -->
                <div class="absolute top-0 left-0 bottom-0 w-1.5 {{ $sim->gas_type === 'amonia' ? 'bg-teal-500/40' : 'bg-emerald-500/40' }}"></div>
                
                <div class="pl-2 space-y-3.5">
                    <!-- Student details & status -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-white text-sm truncate leading-snug">{{ $sim->user->name ?? 'Mahasiswa' }}</h4>
                            <p class="text-[10px] text-slate-500 truncate mt-0.5">{{ $sim->user->email ?? '' }}</p>
                        </div>
                        <!-- Status Badge -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase shrink-0 border {{
                            $sim->status === 'survived' 
                                ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' 
                                : 'bg-rose-500/10 border-rose-500/20 text-rose-450'
                        }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sim->status === 'survived' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            <span>{{ $sim->status }}</span>
                        </span>
                    </div>

                    <!-- Metrics Info -->
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-2.5 bg-slate-950/40 border border-slate-850 rounded-xl">
                            <span class="block text-[9px] text-slate-550 font-bold uppercase tracking-wider">Gas & Durasi</span>
                            <p class="font-bold text-white mt-0.5 truncate capitalize">
                                {{ $sim->gas_type }} <span class="text-[10px] text-slate-400 font-semibold">({{ $sim->duration }}s)</span>
                            </p>
                        </div>
                        <div class="p-2.5 bg-slate-950/40 border border-slate-850 rounded-xl">
                            <span class="block text-[9px] text-slate-550 font-bold uppercase tracking-wider">PPM (Max / Akhir)</span>
                            <p class="font-bold text-white mt-0.5 truncate">
                                {{ $sim->max_ppm }} <span class="text-[10px] text-slate-450 font-semibold">/ {{ $sim->final_ppm }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Barricades and Failure Reason (if failed) -->
                    <div class="flex flex-col gap-1.5 text-xs text-slate-400">
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield" class="w-4 h-4 text-teal-400 shrink-0"></i>
                            <span>APD: <strong class="text-white font-semibold text-[11px]">{{ $sim->ppe_selected }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <i data-lucide="wrench" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                            <span>Mitigasi: <strong class="text-white font-semibold">{{ $sim->mitigation_action === 'water_spray' ? 'Water Spray' : 'Capping Kit' }}</strong></span>
                        </div>
                        @if($sim->status === 'failed' && $sim->failure_reason)
                            <div class="flex items-start gap-2 text-rose-300/80">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-400 shrink-0 mt-0.5"></i>
                                <span>Gagal: <span class="font-medium text-rose-250">{{ $sim->failure_reason }}</span></span>
                            </div>
                        @endif
                    </div>

                    <!-- Timestamp Footer -->
                    <div class="flex items-center gap-1.5 text-[10px] text-slate-500 font-semibold uppercase justify-end pt-1">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        <span>{{ $sim->created_at->format('d M Y - H:i') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-slate-900/30 border border-slate-850 p-8 rounded-2xl text-center space-y-3">
                <i data-lucide="activity" class="w-12 h-12 text-slate-655 mx-auto"></i>
                <p class="text-sm text-slate-500 font-medium">Log simulasi tidak ditemukan.</p>
            </div>
        @endforelse
    </div>

    <!-- DESKTOP TABLE LAYOUT (Hidden on mobile, visible on sm and up) -->
    <div class="hidden sm:block bg-slate-900/40 backdrop-blur-xl border border-slate-800/80 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/40 text-[11px] text-slate-400 font-semibold uppercase tracking-wider">
                        <th class="py-4 px-6">Mahasiswa</th>
                        <th class="py-4 px-6">Jenis Gas</th>
                        <th class="py-4 px-6 text-center">Durasi</th>
                        <th class="py-4 px-6 text-center">PPM Maks</th>
                        <th class="py-4 px-6 text-center">PPM Akhir</th>
                        <th class="py-4 px-6 text-center">APD</th>
                        <th class="py-4 px-6 text-center">Mitigasi</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6">Detail Kegagalan</th>
                        <th class="py-4 px-6 text-right">Tanggal Log</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($simulations as $sim)
                        <tr class="hover:bg-slate-800/25 transition-colors">
                            <!-- Student User Info -->
                            <td class="py-4 px-6">
                                <p class="text-sm font-bold text-white truncate max-w-[150px]" title="{{ $sim->user->name ?? '' }}">{{ $sim->user->name ?? 'Mahasiswa' }}</p>
                                <p class="text-xs text-slate-500 truncate max-w-[150px] mt-0.5" title="{{ $sim->user->email ?? '' }}">{{ $sim->user->email ?? '' }}</p>
                            </td>
                            <!-- Gas Type -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-md border {{
                                    $sim->gas_type === 'amonia' 
                                        ? 'bg-teal-500/10 border-teal-500/20 text-teal-400' 
                                        : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400'
                                }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sim->gas_type === 'amonia' ? 'bg-teal-400' : 'bg-emerald-450' }}"></span>
                                    <span class="capitalize">{{ $sim->gas_type }}</span>
                                </span>
                            </td>
                            <!-- Duration -->
                            <td class="py-4 px-6 text-center text-sm font-semibold text-white">
                                {{ $sim->duration }}s
                            </td>
                            <!-- Max PPM -->
                            <td class="py-4 px-6 text-center text-xs font-semibold text-slate-300">
                                {{ $sim->max_ppm }}
                            </td>
                            <!-- Final PPM -->
                            <td class="py-4 px-6 text-center text-xs font-semibold text-slate-350">
                                {{ $sim->final_ppm }}
                            </td>
                            <!-- APD -->
                            <td class="py-4 px-6 text-center text-xs font-semibold text-slate-300">
                                {{ $sim->ppe_selected }}
                            </td>
                            <!-- Mitigasi -->
                            <td class="py-4 px-6 text-center text-xs font-semibold text-slate-350">
                                {{ $sim->mitigation_action === 'water_spray' ? 'Water Spray' : 'Capping Kit' }}
                            </td>
                            <!-- Status -->
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase {{
                                    $sim->status === 'survived' 
                                        ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' 
                                        : 'bg-rose-500/10 border-rose-500/20 text-rose-450'
                                }}">
                                    <span>{{ $sim->status }}</span>
                                </span>
                            </td>
                            <!-- Failure Reason -->
                            <td class="py-4 px-6 text-xs text-rose-300/80 font-medium">
                                {{ $sim->failure_reason ?? '-' }}
                            </td>
                            <!-- Created Date -->
                            <td class="py-4 px-6 text-right text-xs text-slate-400 font-medium">
                                {{ $sim->created_at->format('d M Y - H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-500 font-medium space-y-3">
                                <i data-lucide="activity" class="w-12 h-12 text-slate-655 mx-auto"></i>
                                <p>Log simulasi tidak ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
