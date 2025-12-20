<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | {{ $siteSettings['site_name'] }}</title>
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .mesh-bg {
            background-color: #f6f6f6;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
        }
    </style>
</head>
<body class="mesh-bg flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md">
        <!-- Logo Section -->
        <div class="text-center mb-12">
            @if(isset($siteSettings['site_logo']))
            <img src="{{ $siteSettings['site_logo'] }}" class="h-24 w-auto mx-auto mb-6 object-contain">
            @else
            <div class="w-16 h-20 bg-primary mx-auto mb-6 flex items-center justify-center rounded-b-xl shadow-2xl">
                <span class="text-secondary font-black text-xl">{{ $siteSettings['site_short_name'] }}</span>
            </div>
            @endif
            <h1 class="text-3xl font-black text-white italic tracking-tighter uppercase font-outfit">{{ $siteSettings['site_name'] }}</h1>
            <p class="text-zinc-400 text-[10px] font-black uppercase tracking-widest mt-2">Admin Administration Panel</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-xl rounded-[2.5rem] border border-white/10 p-10 shadow-2xl">
            <h2 class="text-xl font-bold text-white mb-8">Secure Login</h2>
            
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-white/5 border border-white/5 p-4 rounded-2xl font-bold text-white focus:ring-2 focus:ring-secondary focus:border-transparent outline-none transition text-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" required
                            class="w-full bg-white/5 border border-white/5 p-4 rounded-2xl font-bold text-white focus:ring-2 focus:ring-secondary focus:border-transparent outline-none transition text-sm">
                    </div>
                </div>

                @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-500 p-4 rounded-2xl text-xs font-bold text-center">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <div class="flex items-center justify-between px-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/10 bg-white/5 text-secondary focus:ring-secondary transition">
                        <span class="text-[10px] font-bold text-zinc-400 group-hover:text-zinc-200 transition uppercase tracking-wider">Remember Me</span>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full bg-secondary text-primary font-black py-5 rounded-2xl hover:bg-white transition duration-300 uppercase tracking-widest text-xs shadow-xl flex items-center justify-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Authorize Access
                </button>
            </form>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('home') }}" class="text-[10px] font-black text-zinc-500 hover:text-white transition uppercase tracking-widest flex items-center justify-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Public Site
            </a>
        </div>
    </div>
</body>
</html>
