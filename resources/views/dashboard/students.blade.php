@extends('dashboard.layout')

@section('title', 'Daftar Mahasiswa')

@section('content')
<div class="space-y-6">
    
    <!-- Header Page -->
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-white md:text-3xl">Daftar Mahasiswa</h2>
        <p class="text-slate-400 text-sm mt-1">Daftar mahasiswa terdaftar beserta ringkasan nilai pre-test dan simulasi AR.</p>
    </div>

    <!-- MOBILE CARD LAYOUT (Visible only on mobile screens < 640px) -->
    <div class="block sm:hidden space-y-4">
        @forelse($students as $student)
            <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/80 p-5 rounded-2xl space-y-4 relative overflow-hidden shadow-lg">
                <div class="absolute -top-12 -right-12 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl pointer-events-none"></div>

                <!-- Student Initials & Name -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center border border-slate-700 text-teal-400 font-bold uppercase shrink-0">
                        {{ substr($student->name, 0, 2) }}
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="font-bold text-white truncate text-base">{{ $student->name }}</h4>
                        <p class="text-xs text-slate-500 truncate">{{ $student->email }}</p>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-slate-800/85"></div>

                <!-- Stats Grid inside Mobile Card -->
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="p-2 bg-slate-950/40 rounded-xl border border-slate-850">
                        <span class="block text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Pre-Test</span>
                        <span class="block text-sm font-bold text-violet-400 mt-0.5">
                            {{ $student->pre_test_score }}
                        </span>
                    </div>
                    <div class="p-2 bg-slate-950/40 rounded-xl border border-slate-850">
                        <span class="block text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Simulasi</span>
                        <span class="block text-sm font-bold text-amber-400 mt-0.5">
                            {{ $student->simulations_count }}x
                        </span>
                    </div>
                    <div class="p-2 bg-slate-950/40 rounded-xl border border-slate-850">
                        <span class="block text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Survival</span>
                        <span class="block text-sm font-bold text-emerald-400 mt-0.5">
                            {{ $student->survival_rate }}%
                        </span>
                    </div>
                </div>

                <!-- Join Date Footer -->
                <div class="flex items-center gap-1.5 text-[10px] text-slate-500 font-semibold uppercase justify-end">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    <span>Gabung: {{ $student->created_at->format('d M Y') }}</span>
                </div>
            </div>
        @empty
            <div class="bg-slate-900/30 border border-slate-850 p-8 rounded-2xl text-center space-y-3">
                <i data-lucide="users" class="w-12 h-12 text-slate-650 mx-auto"></i>
                <p class="text-sm text-slate-500 font-medium">Belum ada mahasiswa terdaftar.</p>
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
                        <th class="py-4 px-6 text-center">Rerata Pre-Test</th>
                        <th class="py-4 px-6 text-center">Percobaan Simulasi</th>
                        <th class="py-4 px-6 text-center">Tingkat Survival</th>
                        <th class="py-4 px-6 text-right">Tanggal Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-800/25 transition-colors group">
                            <!-- Name & Email -->
                            <td class="py-4.5 px-6 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center border border-slate-700 text-teal-400 font-bold uppercase transition-colors group-hover:border-teal-500/50">
                                    {{ substr($student->name, 0, 2) }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-white truncate">{{ $student->name }}</p>
                                    <p class="text-xs text-slate-500 truncate mt-0.5">{{ $student->email }}</p>
                                </div>
                            </td>
                            <!-- Pre-Test Score -->
                            <td class="py-4.5 px-6 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-full text-xs font-bold">
                                    {{ $student->pre_test_score }}
                                </span>
                            </td>
                            <!-- Simulations Count -->
                            <td class="py-4.5 px-6 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-full text-xs font-bold">
                                    {{ $student->simulations_count }} Kali
                                </span>
                            </td>
                            <!-- Survival Rate -->
                            <td class="py-4.5 px-6 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full text-xs font-bold">
                                    {{ $student->survival_rate }}%
                                </span>
                            </td>
                            <!-- Created Date -->
                            <td class="py-4.5 px-6 text-right text-xs text-slate-400 font-medium">
                                {{ $student->created_at->format('d F Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500 font-medium space-y-3">
                                <i data-lucide="users" class="w-12 h-12 text-slate-650 mx-auto"></i>
                                <p>Belum ada mahasiswa terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
