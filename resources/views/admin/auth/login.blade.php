<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LC Admin Access | Secure Portal</title>
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $siteSettings['primary_color'] }}',
                        secondary: '{{ $siteSettings['secondary_color'] }}',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .mesh-bg {
            background-color: #0b0b0b;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,20%,1) 0, transparent 40%),
                radial-gradient(at 100% 0%, hsla(339,49%,20%,1) 0, transparent 50%);
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.01);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="mesh-bg flex items-center justify-center min-h-screen p-6" x-data="{ showPass: false }">
    <div class="w-full max-w-md">
        <!-- Logo Section -->
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary p-0.5 rounded-2xl mx-auto mb-6 shadow-2xl animate-pulse-slow">
                <div class="w-full h-full bg-zinc-900 rounded-2xl flex items-center justify-center overflow-hidden">
                    @if(isset($siteSettings['site_logo']))
                    <img src="{{ $siteSettings['site_logo'] }}" class="w-full h-full object-contain p-2">
                    @else
                    <span class="text-secondary font-black text-xl italic">{{ $siteSettings['site_short_name'] }}</span>
                    @endif
                </div>
            </div>
            <h1 class="text-2xl font-black text-white uppercase italic tracking-tighter">LC Admin Access</h1>
            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-2">Secure Management Gateway</p>
        </div>

        <!-- Login Card -->
        <div class="glass-panel rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
            
            <h2 class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-10 border-l-4 border-secondary pl-4">Authorized Personnel Only</h2>
            
            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6 relative">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] px-1">Control Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-zinc-600 group-focus-within:text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-white/[0.02] border border-white/5 p-4 pl-12 rounded-2xl font-bold text-white focus:ring-2 focus:ring-secondary focus:bg-white/5 focus:border-transparent outline-none transition text-sm placeholder:text-zinc-700"
                            placeholder="admin@example.com">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] px-1">Access Token (Password)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-zinc-600 group-focus-within:text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input :type="showPass ? 'text' : 'password'" name="password" required
                            class="w-full bg-white/[0.02] border border-white/5 p-4 pl-12 rounded-2xl font-bold text-white focus:ring-2 focus:ring-secondary focus:bg-white/5 focus:border-transparent outline-none transition text-sm placeholder:text-zinc-700"
                            placeholder="••••••••">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <svg x-show="!showPass" class="h-4 w-4 text-zinc-600 hover:text-secondary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPass" class="h-4 w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.04m4.066-1.56a10.048 10.048 0 012.313-.468M15.312 4.125A9.957 9.957 0 0112 4a10.015 10.015 0 012.23.248m-1.238 1.238a3 3 0 11-4.243 4.243m4.242-4.243L8 16m2-8l-4 4"/></svg>
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-500 p-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-center">
                    {{ $errors->first() }}
                </div>
                @endif

                <div class="flex items-center justify-between px-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded-lg border-white/10 bg-white/5 text-secondary focus:ring-secondary transition-all">
                        <span class="text-[10px] font-black text-zinc-500 group-hover:text-zinc-300 transition uppercase tracking-widest">Maintain Session</span>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full bg-secondary text-primary font-black py-5 rounded-2xl hover:bg-white transition-all duration-500 uppercase tracking-[0.2em] text-[10px] shadow-2xl flex items-center justify-center gap-3 hover:-translate-y-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Authorize Command
                </button>
            </form>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('home') }}" class="text-[9px] font-black text-zinc-600 hover:text-zinc-400 transition uppercase tracking-[0.3em] flex items-center justify-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Return to Surface Site
            </a>
        </div>
    </div>
</body>
</html>
