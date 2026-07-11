<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Dosen - Hazard K3 Mitigasi</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4 {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-zinc-950 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased selection:bg-teal-500/30 selection:text-teal-200">

    <!-- Login Container -->
    <div class="w-full max-w-md bg-slate-900/60 backdrop-blur-2xl border border-slate-800/80 p-6 md:p-8 rounded-3xl shadow-2xl relative overflow-hidden">
        
        <!-- Background Neon Glow -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Logo/Header -->
        <div class="flex flex-col items-center text-center mb-8 relative z-10">
            <div class="p-3 bg-gradient-to-tr from-teal-500 to-emerald-500 rounded-2xl shadow-xl shadow-teal-500/10 mb-4 inline-flex">
                <i data-lucide="shield-alert" class="w-8 h-8 text-slate-950 stroke-[2.5]"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">HAZARD K3 MITIGASI</h1>
            <p class="text-xs text-slate-500 font-semibold tracking-wider uppercase mt-1">Dashboard Dosen / Admin</p>
        </div>

        <!-- Alert messages -->
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm font-medium">
                <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 flex items-start gap-3 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-sm font-medium">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                <div>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form action="/login" method="POST" class="space-y-5 relative z-10">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 pointer-events-none">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="block w-full pl-10 pr-4 py-3 bg-slate-950/40 border border-slate-800 rounded-xl text-sm text-slate-100 placeholder-slate-650 focus:outline-none focus:border-teal-500/60 focus:ring-1 focus:ring-teal-500/60 transition-all duration-300"
                           placeholder="nama@email.com">
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 pointer-events-none">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </span>
                    <input type="password" name="password" id="password" required
                           class="block w-full pl-10 pr-4 py-3 bg-slate-950/40 border border-slate-800 rounded-xl text-sm text-slate-100 placeholder-slate-650 focus:outline-none focus:border-teal-500/60 focus:ring-1 focus:ring-teal-500/60 transition-all duration-300"
                           placeholder="••••••••">
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" 
                       class="h-4 w-4 rounded bg-slate-950/40 border-slate-800 text-teal-500 focus:ring-teal-500/30 focus:ring-offset-slate-900">
                <label for="remember" class="ml-2 text-xs text-slate-400 cursor-pointer font-medium select-none">Ingat perangkat saya</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-600 hover:to-emerald-600 text-slate-950 font-bold rounded-xl shadow-lg shadow-teal-500/10 hover:shadow-teal-500/20 active:scale-[0.98] transition-all duration-350 cursor-pointer">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                <span>Masuk Sekarang</span>
            </button>
        </form>

        <!-- Back to info -->
        <div class="mt-8 text-center text-xs text-slate-600 font-medium">
            <p>&copy; 2026 Hazard K3 Mitigasi. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
