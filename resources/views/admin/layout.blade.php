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
        
        /* Custom Scrollbar for Admin Nav */
        .admin-nav-scrollbar::-webkit-scrollbar { width: 4px; }
        .admin-nav-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .admin-nav-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        .admin-nav-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }
        .admin-nav-scrollbar { scrollbar-width: thin; scrollbar-color: rgba(255, 255, 255, 0.2) transparent; }

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
    @stack('styles')
</head>
<body x-data="{ mobileMenuOpen: false }" class="antialiased bg-zinc-100 flex min-h-screen">
    
    <!-- Sidebar -->
    <aside class="bg-primary text-white w-64 flex flex-col fixed h-full z-50 overflow-hidden transition-all duration-300 ease-in-out border-r border-white/5 shadow-2xl">
        
        <div class="shrink-0 p-6 flex items-center gap-3 border-b border-primary-light bg-primary">
            @if(isset($siteSettings['site_logo']))
            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center p-1 shrink-0">
                <img src="{{ $siteSettings['site_logo'] }}" class="max-w-full max-h-full object-contain">
            </div>
            @else
            <div class="w-10 h-10 bg-secondary rounded-xl flex items-center justify-center font-black text-primary text-xs tracking-tighter shadow-lg shrink-0">
                {{ $siteSettings['site_short_name'] }}
            </div>
            @endif

            <div class="overflow-hidden whitespace-nowrap">
                <span class="block text-xs font-black text-white italic tracking-tighter leading-none">{{ $siteSettings['site_name'] }}</span>
                <span class="text-[8px] font-bold text-secondary uppercase tracking-[0.2em]">Tournament Admin</span>
            </div>
        </div>

        <!-- Navigation Section -->
        <nav x-data="{ 
            openSections: {
                analytics: {{ request()->routeIs('admin.analytics.*', 'admin.stats.*', 'admin.predictor.*') ? 'true' : 'false' }},
                news: {{ request()->routeIs('admin.news.*', 'admin.comments.*', 'admin.interviews.*', 'admin.stories.*') ? 'true' : 'false' }},
                settings: {{ request()->routeIs('admin.settings.*', 'admin.sponsors.*') ? 'true' : 'false' }}
            }
        }" class="flex-1 min-h-0 px-4 py-6 space-y-1 overflow-y-auto admin-nav-scrollbar">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.competitions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.competitions.*') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Competitions
            </a>
            <a href="{{ route('admin.groups.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.groups.*') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Groups
            </a>
            <a href="{{ route('admin.teams') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.teams') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Teams
            </a>
            <a href="{{ route('admin.players.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.players.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Players
            </a>
            <a href="{{ route('admin.referees.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.referees.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Referees
            </a>
            <a href="{{ route('admin.live-console') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.live-console*') ? 'bg-rose-600 text-white font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <div class="relative shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    @if($isAnyMatchLive ?? false)
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full animate-ping"></span>
                    @endif
                </div>
                <span class="font-black">LIVE MATCH</span>
            </a>
            <a href="{{ route('admin.fixtures') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.fixtures') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Fixtures / Results
            </a>

            <!-- Analytics Section -->
            <button @click="openSections.analytics = !openSections.analytics" class="w-full flex items-center justify-between pt-6 pb-2 px-4 group outline-none">
                <span class="text-[10px] font-black text-white/30 group-hover:text-white/60 transition uppercase tracking-widest text-left">Analytics</span>
                <svg :class="openSections.analytics ? 'rotate-180' : ''" class="w-3 h-3 text-white/20 group-hover:text-white/40 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openSections.analytics" x-transition.opacity.duration.300ms class="space-y-1">
            <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.analytics.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                Web Analytics
            </a>

            <a href="{{ route('admin.stats.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.stats.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                Tournament Stats
            </a>

            <a href="{{ route('admin.predictor.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.predictor.*') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                Predictor League
            </a>
            </div>

            <!-- News Management Section -->
            <button @click="openSections.news = !openSections.news" class="w-full flex items-center justify-between pt-6 pb-2 px-4 group outline-none">
                <span class="text-[10px] font-black text-white/30 group-hover:text-white/60 transition uppercase tracking-widest text-left">News Management</span>
                <svg :class="openSections.news ? 'rotate-180' : ''" class="w-3 h-3 text-white/20 group-hover:text-white/40 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openSections.news" x-transition.opacity.duration.300ms class="space-y-1">
            <a href="{{ route('admin.news.index') }}" 
                class="flex items-center gap-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.news.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}"
                :class="sidebarCollapsed ? 'justify-center py-3 px-0' : 'px-4 py-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="whitespace-nowrap overflow-hidden">All Posts</span>
            </a>
            <a href="{{ route('admin.comments.index') }}" 
                class="flex items-center justify-between rounded-xl transition-all duration-300 {{ request()->routeIs('admin.comments.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}"
                :class="sidebarCollapsed ? 'justify-center py-3 px-0' : 'px-4 py-3'">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="whitespace-nowrap overflow-hidden">Comments</span>
                </div>
                @if(isset($pendingCommentsCount) && $pendingCommentsCount > 0)
                <span x-show="!sidebarCollapsed" class="bg-rose-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow-lg border border-white/20">{{ $pendingCommentsCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.news.create') }}" 
                class="flex items-center gap-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.news.create') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}"
                :class="sidebarCollapsed ? 'justify-center py-3 px-0' : 'px-4 py-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="whitespace-nowrap overflow-hidden">Add Post</span>
            </a>
            
            <a href="{{ route('admin.interviews.index') }}" class="flex items-center gap-3 px-8 py-2 rounded-xl transition {{ request()->routeIs('admin.interviews.*') ? 'bg-secondary/10 text-secondary font-bold' : 'hover:bg-primary-light text-zinc-400' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span class="text-xs">Interviews</span>
            </a>
            <a href="{{ route('admin.stories.index') }}" class="flex items-center gap-3 px-8 py-2 rounded-xl transition {{ request()->routeIs('admin.stories.index') ? 'bg-secondary/10 text-secondary font-bold' : 'hover:bg-primary-light text-zinc-400' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span class="text-xs">Stories</span>
            </a>

            <a href="{{ route('admin.news.categories') }}" 
                class="flex items-center gap-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.news.categories') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}"
                :class="sidebarCollapsed ? 'justify-center py-3 px-0' : 'px-4 py-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="whitespace-nowrap overflow-hidden">Categories</span>
            </a>
            <a href="{{ route('admin.news.tags') }}" 
                class="flex items-center gap-3 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.news.tags') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}"
                :class="sidebarCollapsed ? 'justify-center py-3 px-0' : 'px-4 py-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="whitespace-nowrap overflow-hidden">Tags</span>
            </a>
            </div>

            <!-- Site Settings Section -->
            <button @click="openSections.settings = !openSections.settings" class="w-full flex items-center justify-between pt-6 pb-2 px-4 group outline-none">
                <span class="text-[10px] font-black text-white/30 group-hover:text-white/60 transition uppercase tracking-widest text-left">Site Settings</span>
                <svg :class="openSections.settings ? 'rotate-180' : ''" class="w-3 h-3 text-white/20 group-hover:text-white/40 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openSections.settings" x-transition.opacity.duration.300ms class="space-y-1">
                <a href="{{ route('admin.sponsors.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.sponsors.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Sponsorship
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.settings.index') ? 'bg-secondary text-primary font-bold' : 'hover:bg-primary-light text-zinc-300' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    General Settings
                </a>
            </div>
        </nav>

        <div class="shrink-0 p-6 border-t border-primary-light bg-primary">
            <a href="{{ route('home') }}" class="text-xs font-bold text-zinc-400 hover:text-white transition flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Exit to Website
            </a>
        </div>
    </aside>

    <main class="ml-64 flex-1 p-12 transition-all duration-300 ease-in-out">
        
        <header class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-3xl font-black text-primary tracking-tighter uppercase">@yield('title')</h1>
                <p class="text-zinc-400 text-sm font-medium">Tournament Management Dashboard</p>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <span class="block text-xs font-black text-primary uppercase">{{ auth()->user()->name }}</span>
                    <span class="block text-[10px] text-zinc-400 font-bold uppercase tracking-widest">{{ auth()->user()->email }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="w-10 h-10 bg-rose-500 text-white rounded-xl flex items-center justify-center hover:bg-rose-600 transition shadow-lg" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </header>

        @if(session('success'))
        <div class="mb-8 bg-zinc-900 text-secondary px-8 py-4 rounded-2xl font-black shadow-lg flex items-center justify-between animate-fade-in border-l-4 border-secondary">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-white/40 hover:text-white">✕</button>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-8 bg-rose-500 text-white px-8 py-4 rounded-2xl font-black shadow-lg flex items-center justify-between animate-fade-in border-l-4 border-white">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white/40 hover:text-white">✕</button>
        </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
