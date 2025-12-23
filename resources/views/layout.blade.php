<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Igbuzo Sports Competition')</title>
    @if(isset($siteSettings['favicon']))
    <link rel="icon" href="{{ $siteSettings['favicon'] }}" type="image/x-icon">
    @endif
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (via CDN for rapid deployment) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
    <style>
        body { background-color: #f6f6f6; }
        .hero-gradient { background: linear-gradient(135deg, {{ $siteSettings['primary_color'] }} 0%, {{ $siteSettings['primary_color'] }} 100%); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        @keyframes gradient-xy {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-gradient-xy {
            background-size: 400% 400%;
            animation: gradient-xy 15s ease infinite;
        }
    </style>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased min-h-screen" style="background-color: #e5e7eb;" x-data="{ 
    mobileMenuOpen: false,
    videoModalOpen: false, 
    videoUrl: '',
    getEmbedUrl(url) {
        if (!url) return '';
        let videoId = '';
        if (url.includes('youtube.com') || url.includes('youtu.be')) {
            videoId = url.includes('v=') ? url.split('v=')[1].split('&')[0] : url.split('/').pop();
            return `https://www.youtube.com/embed/${videoId}?autoplay=1`;
        } else if (url.includes('v=vimeo.com') || url.includes('vimeo.com')) {
            videoId = url.split('/').pop();
            return `https://player.vimeo.com/video/${videoId}?autoplay=1`;
        }
        return url;
    }
}">
    <!-- Top Utility Bar -->
    <div class="bg-white border-b border-zinc-100 hidden md:block">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 h-10 flex items-center justify-between text-[11px] font-medium text-zinc-500">
            <div class="flex gap-6">
                <a href="#" class="hover:text-primary transition">Community Championship</a>
                <a href="#" class="hover:text-primary transition">About Us</a>
                <a href="{{ route('news.index') }}" class="text-[11px] font-bold text-zinc-600 hover:text-primary transition uppercase tracking-widest {{ request()->routeIs('news.*') ? 'text-primary' : '' }}">News</a>
                <a href="#" class="hover:text-primary transition">Youth</a>
            </div>
            <div class="flex gap-4 items-center">
                <span>Official Competition Site</span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white sticky top-0 z-50 border-b border-zinc-100">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 h-16 md:h-20 flex items-center justify-between">
            <div class="flex items-center gap-6 lg:gap-12">
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                    @if(isset($siteSettings['site_logo']))
                    <img src="{{ $siteSettings['site_logo'] }}" class="h-10 md:h-12 w-auto object-contain">
                    @else
                    <div class="w-8 h-10 md:w-10 md:h-12 bg-primary flex items-center justify-center rounded-b-lg shadow-lg">
                        <span class="text-secondary font-black text-[10px] md:text-xs leading-none">{{ $siteSettings['site_short_name'] }}</span>
                    </div>
                    @endif
                    <span class="text-primary font-black text-lg md:text-xl italic tracking-tighter hidden sm:block">{{ $siteSettings['site_name'] }}</span>
                </a>

                <nav class="hidden md:flex items-center gap-4 lg:gap-8 text-[14px] lg:text-[15px] font-bold text-primary">
                    <a href="{{ route('home') }}" class="hover:text-secondary transition {{ request()->routeIs('home') ? 'text-secondary' : '' }}">Home</a>
                    <a href="{{ route('fixtures') }}" class="hover:text-secondary transition {{ request()->routeIs('fixtures') ? 'text-secondary' : '' }}">Matches</a>
                    <a href="{{ route('results') }}" class="hover:text-secondary transition {{ request()->routeIs('results') ? 'text-secondary' : '' }}">Results</a>
                    <a href="{{ route('table') }}" class="hover:text-secondary transition {{ request()->routeIs('table') ? 'text-secondary' : '' }}">Table</a>
                    <a href="{{ route('knockout') }}" class="hover:text-secondary transition {{ request()->routeIs('knockout') ? 'text-secondary' : '' }}">Knockout</a>
                    <a href="{{ route('stats') }}" class="hover:text-secondary transition {{ request()->routeIs('stats') ? 'text-secondary' : '' }}">Stats</a>
                    <a href="{{ route('gallery') }}" class="hover:text-secondary transition {{ request()->routeIs('gallery') ? 'text-secondary' : '' }}">Gallery</a>
                    <a href="{{ route('news.index') }}" class="hover:text-secondary transition {{ request()->routeIs('news.index') ? 'text-secondary' : '' }}">News</a>
                    <a href="{{ route('teams') }}" class="hover:text-secondary transition {{ request()->routeIs('teams') ? 'text-secondary' : '' }}">Teams</a>
                </nav>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <a href="{{ route('admin.dashboard') }}" class="hidden sm:block bg-primary text-white text-[10px] md:text-xs font-bold px-4 py-2 rounded-full hover:bg-primary-light transition">Sign In</a>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-primary hover:bg-zinc-50 rounded-lg transition">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-white border-b border-zinc-100 absolute w-full z-40"
             style="display: none;">
            <nav class="flex flex-col p-4 gap-2 text-sm font-bold text-primary">
                <a href="{{ route('home') }}" class="p-3 hover:bg-zinc-50 rounded-xl transition {{ request()->routeIs('home') ? 'bg-zinc-50 text-secondary border-l-4 border-secondary pl-2' : '' }}">Home</a>
                <a href="{{ route('fixtures') }}" class="p-3 hover:bg-zinc-50 rounded-xl transition {{ request()->routeIs('fixtures') ? 'bg-zinc-50 text-secondary border-l-4 border-secondary pl-2' : '' }}">Matches</a>
                <a href="{{ route('results') }}" class="p-3 hover:bg-zinc-50 rounded-xl transition {{ request()->routeIs('results') ? 'bg-zinc-50 text-secondary border-l-4 border-secondary pl-2' : '' }}">Results</a>
                <a href="{{ route('table') }}" class="p-3 hover:bg-zinc-50 rounded-xl transition {{ request()->routeIs('table') ? 'bg-zinc-50 text-secondary border-l-4 border-secondary pl-2' : '' }}">Table</a>
                <a href="{{ route('knockout') }}" class="p-3 hover:bg-zinc-50 rounded-xl transition {{ request()->routeIs('knockout') ? 'bg-zinc-50 text-secondary border-l-4 border-secondary pl-2' : '' }}">Knockout</a>
                <a href="{{ route('stats') }}" class="p-3 hover:bg-zinc-50 rounded-xl transition {{ request()->routeIs('stats') ? 'bg-zinc-50 text-secondary border-l-4 border-secondary pl-2' : '' }}">Stats</a>
                <a href="{{ route('gallery') }}" class="p-3 hover:bg-zinc-50 rounded-xl transition {{ request()->routeIs('gallery') ? 'bg-zinc-50 text-secondary border-l-4 border-secondary pl-2' : '' }}">Gallery</a>
                <a href="{{ route('news.index') }}" class="p-3 hover:bg-zinc-50 rounded-xl transition {{ request()->routeIs('news.index') ? 'bg-zinc-50 text-secondary border-l-4 border-secondary pl-2' : '' }}">News</a>
                <a href="{{ route('teams') }}" class="p-3 hover:bg-zinc-50 rounded-xl transition {{ request()->routeIs('teams') ? 'bg-zinc-50 text-secondary border-l-4 border-secondary pl-2' : '' }}">Teams</a>
                <hr class="my-2 border-zinc-100">
                <a href="{{ route('admin.dashboard') }}" class="p-3 bg-primary text-white text-center rounded-xl font-black uppercase tracking-widest text-[10px]">Sign In</a>
            </nav>
        </div>
    </header>

    <!-- News Ticker Placeholder -->
    <div class="bg-white border-b border-zinc-100">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 py-3 flex items-center gap-4 text-[10px] md:text-xs font-bold text-zinc-600 overflow-x-auto no-scrollbar whitespace-nowrap">
            <span class="text-secondary shrink-0">LATEST:</span>
            <a href="#" class="hover:underline">Final round approaching as Dragons top Group A</a>
            <span class="text-zinc-200">|</span>
            <a href="#" class="hover:underline">New youth academy opens this weekend</a>
            <span class="text-zinc-200">|</span>
            <a href="#" class="hover:underline">Top scorer race heats up with 3 games left</a>
        </div>
    </div>

    <main class="max-w-[1400px] mx-auto px-4 sm:px-6 py-8">
        @yield('content')
    </main>

    <!-- Sponsor Bar (Redesigned - Premier League Style) -->
    <section class="bg-white border-y border-zinc-100 py-8">
        <div class="max-w-[1400px] mx-auto px-6">
            @if(count($globalSponsors) > 0)
            <div class="flex flex-wrap justify-center items-center gap-8 lg:gap-16">
                @foreach($globalSponsors as $sponsor)
                <div class="flex flex-col items-center gap-3 grayscale hover:grayscale-0 transition duration-500 cursor-pointer group">
                    <img src="{{ $sponsor->logo_url }}" alt="{{ $sponsor->name }}" class="h-12 object-contain group-hover:scale-110 transition">
                    <span class="text-[9px] font-black uppercase tracking-widest text-zinc-300 group-hover:text-primary transition">{{ $sponsor->level }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center">
                <span class="text-[9px] font-black uppercase tracking-widest text-zinc-300 italic">Tournament Partnership Opportunities Available</span>
            </div>
            @endif
        </div>
    </section>

    <!-- Interviews Section -->
    @if(count($globalInterviews) > 0)
    <section class="bg-zinc-50 py-16">
        <div class="max-w-[1400px] mx-auto px-6">
            <h2 class="text-3xl font-black text-primary uppercase tracking-tight mb-8">Interviews</h2>
            
            <div class="relative">
                <div class="flex gap-4 overflow-x-auto pb-6 snap-x snap-mandatory no-scrollbar">
                    @foreach($globalInterviews as $interview)
                    <div class="flex-none w-56 snap-start group">
                        <div @click="videoUrl = getEmbedUrl('{{ $interview->video_url }}'); videoModalOpen = true" class="block relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:scale-[1.02] cursor-pointer">
                            <!-- Thumbnail -->
                            <div class="aspect-[3/4] bg-zinc-800 relative overflow-hidden">
                                @if($interview->thumbnail_url)
                                <img src="{{ $interview->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                @else
                                <div class="w-full h-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                                    <span class="text-white/20 text-6xl">▶</span>
                                </div>
                                @endif
                                
                                <!-- Dark Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                
                                <!-- Play Icon -->
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <div class="w-12 h-12 bg-secondary rounded-full flex items-center justify-center shadow-2xl scale-75 group-hover:scale-100 transition duration-300">
                                        <svg class="w-6 h-6 text-primary fill-current ml-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>

                                <!-- NEW Badge -->
                                @if($interview->is_featured)
                                <div class="absolute top-4 left-4">
                                    <span class="bg-[#6d28d9] text-white text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest">NEW</span>
                                </div>
                                @endif
                                
                                <!-- Content Overlay -->
                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                    <h3 class="text-white font-black text-lg leading-tight mb-2 line-clamp-2">{{ $interview->title }}</h3>
                                    <p class="text-white/80 text-sm font-bold">{{ $interview->interviewee_name }}</p>
                                    @if($interview->interviewee_role)
                                    <p class="text-white/60 text-xs font-medium">{{ $interview->interviewee_role }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <footer class="bg-zinc-900 text-zinc-500 py-12 mt-20">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm space-y-4">
            <p>{{ $siteSettings['footer_text'] }}</p>
            <p class="text-zinc-600 text-[11px] font-bold uppercase tracking-widest">{{ $siteSettings['copyright_text'] }}</p>
        </div>
    </footer>

    <!-- Global Video Modal -->
    <div x-show="videoModalOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-12 overflow-hidden"
         x-cloak>
        <!-- Overlay -->
        <div x-show="videoModalOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             @click="videoModalOpen = false; videoUrl = ''"
             class="absolute inset-0 bg-primary/95 backdrop-blur-sm"></div>

        <!-- Modal Content -->
        <div x-show="videoModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-5xl aspect-video bg-black rounded-3xl shadow-2xl overflow-hidden border border-white/10">
            
            <button @click="videoModalOpen = false; videoUrl = ''" class="absolute top-4 right-4 z-10 w-10 h-10 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition backdrop-blur-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <iframe x-show="videoUrl" :src="videoUrl" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</body>
</html>
