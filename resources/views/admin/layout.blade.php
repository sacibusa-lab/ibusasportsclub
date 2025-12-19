<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LC Admin - @yield('title')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        :root {
            --primary: {{ $siteSettings['primary_color'] }};
            --secondary: {{ $siteSettings['secondary_color'] }};
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $siteSettings['primary_color'] }}',
                        'primary-light': '{{ $siteSettings['primary_color'] }}',
                        secondary: '{{ $siteSettings['secondary_color'] }}',
                        accent: '{{ $siteSettings['accent_color'] }}',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="antialiased bg-zinc-100 flex min-h-screen">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-primary text-white flex flex-col fixed h-full z-50">
        <div class="p-8">
            <div class="px-8 py-8 border-b border-primary-light flex items-center gap-3">
            @if(isset($siteSettings['site_logo']))
            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center p-1">
                <img src="{{ $siteSettings['site_logo'] }}" class="max-w-full max-h-full object-contain">
            </div>
            @else
            <div class="w-10 h-10 bg-secondary rounded-xl flex items-center justify-center font-black text-primary text-xs tracking-tighter shadow-lg">
                {{ $siteSettings['site_short_name'] }}
            </div>
            @endif
            <div>
                <span class="block text-xs font-black text-white italic tracking-tighter leading-none">{{ $siteSettings['site_name'] }}</span>
                <span class="text-[8px] font-bold text-secondary uppercase tracking-[0.2em]">Tournament Admin</span>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.teams') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.teams') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Teams
            </a>
            <a href="{{ route('admin.players.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.players.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Players
            </a>
            <a href="{{ route('admin.fixtures') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.fixtures') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Fixtures / Results
            </a>

            <div class="pt-6 pb-2 px-4">
                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">News Management</span>
            </div>
            <a href="{{ route('admin.news.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.news.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                All Posts
            </a>
            <a href="{{ route('admin.news.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.news.create') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Post
            </a>
            <a href="{{ route('admin.news.categories') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.news.categories') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Categories
            </a>
            <a href="{{ route('admin.news.tags') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.news.tags') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Tags
            </a>

            <div class="pt-6 pb-2 px-4">
                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Site Settings</span>
            </div>
            <a href="{{ route('admin.sponsors.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.sponsors.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Sponsorship
            </a>
            <a href="{{ route('admin.interviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.interviews.*') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Interviews
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.settings.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                General Settings
            </a>
        </nav>

        <div class="p-6 border-t border-primary-light">
            <a href="{{ route('home') }}" class="text-xs font-bold text-zinc-400 hover:text-white transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Exit to Website
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-12">
        <header class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-3xl font-black text-primary tracking-tighter uppercase">@yield('title')</h1>
                <p class="text-zinc-400 text-sm font-medium">Tournament Management Dashboard</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <span class="block text-xs font-black text-primary uppercase">LC Admin</span>
                    <span class="block text-[10px] text-zinc-400 font-bold">Authorized Session</span>
                </div>
                <div class="w-10 h-10 bg-zinc-200 rounded-full"></div>
            </div>
        </header>

        @if(session('success'))
        <div class="mb-8 bg-zinc-900 text-secondary px-8 py-4 rounded-2xl font-black shadow-lg flex items-center justify-between animate-fade-in">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-white/40 hover:text-white">✕</button>
        </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
