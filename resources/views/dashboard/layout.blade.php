<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Hazard K3 Mitigasi</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Vite or CDN fallback just in case) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.6);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.5);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-zinc-950 text-slate-100 min-h-screen flex flex-col md:flex-row antialiased selection:bg-teal-500/30 selection:text-teal-200">

    <!-- DESKTOP SIDEBAR -->
    <aside class="hidden md:flex flex-col w-64 bg-slate-900/40 backdrop-blur-xl border-r border-slate-800/80 p-6 min-h-screen sticky top-0 shrink-0">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-8">
            <div class="p-2 bg-gradient-to-tr from-teal-500 to-emerald-500 rounded-xl shadow-lg shadow-teal-500/20">
                <i data-lucide="shield-alert" class="w-6 h-6 text-slate-950 stroke-[2.5]"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold leading-none tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">HAZARD K3</h1>
                <p class="text-[10px] text-slate-500 font-semibold tracking-widest uppercase">Mitigasi WebAR</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex flex-col gap-2 flex-grow">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-teal-500/20 to-emerald-500/10 text-teal-400 border border-teal-500/20 shadow-md shadow-teal-500/5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40 border border-transparent' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('dashboard.students') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('dashboard.students') ? 'bg-gradient-to-r from-teal-500/20 to-emerald-500/10 text-teal-400 border border-teal-500/20 shadow-md shadow-teal-500/5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40 border border-transparent' }}">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span>Daftar Mahasiswa</span>
            </a>
            <a href="{{ route('dashboard.pre-tests') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('dashboard.pre-tests') ? 'bg-gradient-to-r from-teal-500/20 to-emerald-500/10 text-teal-400 border border-teal-500/20 shadow-md shadow-teal-500/5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40 border border-transparent' }}">
                <i data-lucide="file-check-2" class="w-5 h-5"></i>
                <span>Hasil Pre-Test</span>
            </a>
            <a href="{{ route('dashboard.simulations') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium {{ request()->routeIs('dashboard.simulations') ? 'bg-gradient-to-r from-teal-500/20 to-emerald-500/10 text-teal-400 border border-teal-500/20 shadow-md shadow-teal-500/5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40 border border-transparent' }}">
                <i data-lucide="activity" class="w-5 h-5"></i>
                <span>Log Simulasi AR</span>
            </a>
        </nav>

        <!-- User Info & Logout -->
        <div class="mt-auto border-t border-slate-800/80 pt-4 flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-850 flex items-center justify-center border border-slate-700 text-teal-400 font-bold uppercase">
                    {{ substr(Auth::user()->name ?? 'D', 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name ?? 'Dosen K3' }}</p>
                    <p class="text-[10px] text-slate-500 font-bold uppercase">Dosen</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900/30 hover:bg-rose-500/10 text-slate-400 hover:text-rose-400 border border-slate-800 hover:border-rose-500/20 rounded-xl transition-all duration-300 font-medium cursor-pointer">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE HEADER -->
    <header class="flex md:hidden items-center justify-between px-6 py-4 bg-slate-900/60 backdrop-blur-xl border-b border-slate-850 sticky top-0 z-40">
        <div class="flex items-center gap-2">
            <div class="p-1.5 bg-gradient-to-tr from-teal-500 to-emerald-500 rounded-lg">
                <i data-lucide="shield-alert" class="w-4 h-4 text-slate-950 stroke-[2.5]"></i>
            </div>
            <h1 class="text-sm font-bold tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">HAZARD K3</h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-[11px] font-semibold px-2 py-1 bg-slate-800 text-teal-400 border border-slate-700 rounded-md">Dosen</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-450 transition-colors" title="Logout">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-grow p-4 md:p-8 overflow-y-auto pb-24 md:pb-8 max-w-full">
        <!-- Toast Notifications -->
        @if(session('success'))
            <div id="toast-success" class="mb-6 flex items-center gap-3 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl animate-fade-in backdrop-blur-md">
                <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
                <button onclick="document.getElementById('toast-success').remove()" class="ml-auto text-emerald-400/60 hover:text-emerald-400">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-slate-950/80 backdrop-blur-2xl border-t border-slate-900/90 py-2.5 px-4 flex items-center justify-around z-45">
        <a href="{{ route('dashboard') }}" 
           class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('dashboard') ? 'text-teal-400' : 'text-slate-400 hover:text-slate-300' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span class="text-[9px] font-medium tracking-wide">Beranda</span>
        </a>
        <a href="{{ route('dashboard.students') }}" 
           class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('dashboard.students') ? 'text-teal-400' : 'text-slate-400 hover:text-slate-300' }}">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span class="text-[9px] font-medium tracking-wide">Mahasiswa</span>
        </a>
        <a href="{{ route('dashboard.pre-tests') }}" 
           class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('dashboard.pre-tests') ? 'text-teal-400' : 'text-slate-400 hover:text-slate-300' }}">
            <i data-lucide="file-check-2" class="w-5 h-5"></i>
            <span class="text-[9px] font-medium tracking-wide">Pre-Test</span>
        </a>
        <a href="{{ route('dashboard.simulations') }}" 
           class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('dashboard.simulations') ? 'text-teal-400' : 'text-slate-400 hover:text-slate-300' }}">
            <i data-lucide="activity" class="w-5 h-5"></i>
            <span class="text-[9px] font-medium tracking-wide">Simulasi</span>
        </a>
    </nav>

    <script>
        // Initialize Lucide icons on page load
        lucide.createIcons();
    </script>
</body>
</html>
