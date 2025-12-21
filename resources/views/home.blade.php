@extends('layout')

@section('content')
@if(count($stories) > 0)
    @include('components.stories')
@endif

<!-- Upcoming Matches Carousel (Full Width) -->
<div class="col-span-12 mb-8 -mx-4 sm:mx-0">
    <h2 class="text-xl md:text-2xl font-black text-primary uppercase tracking-tighter mb-6 px-4 sm:px-0">Next Matches</h2>
    <div class="flex gap-4 md:gap-6 overflow-x-auto pb-6 snap-x snap-mandatory no-scrollbar px-4 sm:px-0">
        @forelse($upcomingMatches as $match)
        <div class="flex-none w-[300px] md:w-[350px] snap-start">
            <div class="bg-white rounded-lg shadow-sm border border-zinc-200 overflow-hidden group hover:shadow-md transition-all duration-300">
                <!-- Header -->
                <div class="px-5 py-3 bg-zinc-50 border-b border-zinc-100 flex justify-between items-center">
                    <span class="text-[9px] md:text-[10px] font-black text-zinc-800 uppercase tracking-widest">{{ $match->match_date->format('D d.m.Y') }}</span>
                    <span class="text-[9px] md:text-[10px] font-black text-zinc-800 uppercase tracking-widest">{{ $match->match_date->format('H:i') }}</span>
                </div>

                <!-- Body -->
                <div class="p-5 flex items-center justify-between">
                    <!-- Match Info -->
                    <div class="flex items-center gap-4 flex-1">
                        <div class="text-center w-10 md:w-12">
                            <span class="block text-base md:text-lg font-black text-zinc-900 uppercase tracking-tighter mb-1">{{ substr(strtoupper($match->homeTeam->short_name ?? substr($match->homeTeam->name, 0, 3)), 0, 3) }}</span>
                            @if($match->homeTeam->logo_url)
                            <img src="{{ $match->homeTeam->logo_url }}" class="w-6 h-6 md:w-8 md:h-8 object-contain mx-auto">
                            @endif
                        </div>
                        
                        <span class="text-[10px] font-bold text-zinc-400 uppercase">vs</span>

                        <div class="text-center w-10 md:w-12">
                            @if($match->awayTeam->logo_url)
                            <img src="{{ $match->awayTeam->logo_url }}" class="w-6 h-6 md:w-8 md:h-8 object-contain mx-auto mb-1">
                            @endif
                            <span class="block text-base md:text-lg font-black text-zinc-900 uppercase tracking-tighter">{{ substr(strtoupper($match->awayTeam->short_name ?? substr($match->awayTeam->name, 0, 3)), 0, 3) }}</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="w-px h-12 bg-zinc-100 mx-3 md:mx-4"></div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-1.5 md:gap-2">
                        <button class="flex items-center gap-2 text-[9px] md:text-[10px] font-bold text-zinc-600 hover:text-primary transition uppercase tracking-wide">
                            <span class="w-3">📊</span> Compare
                        </button>
                        <button class="flex items-center gap-2 text-[9px] md:text-[10px] font-bold text-zinc-600 hover:text-primary transition uppercase tracking-wide">
                            <span class="w-3">⚽</span> Previous
                        </button>
                    </div>
                </div>

                <!-- Footer Button -->
                <button class="w-full bg-[#ff4b4b] hover:bg-[#ff3333] text-white text-[10px] md:text-[11px] font-black uppercase tracking-widest py-3 transition text-center">
                    Where to watch LIVE
                </button>
            </div>
        </div>
        @empty
        <div class="w-full py-12 text-center bg-zinc-50 rounded-xl border-2 border-dashed border-zinc-100">
            <span class="text-xs font-black text-zinc-300 uppercase tracking-widest italic">No upcoming matches scheduled</span>
        </div>
        @endforelse
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Main Editorial Hero (Left 8 Columns - Expanded) -->
    <div class="lg:col-span-8">
        @if($heroPost)
        <a href="{{ route('news.show', $heroPost->slug) }}" class="bg-primary rounded-2xl shadow-xl overflow-hidden h-full flex flex-col relative group block min-h-[350px] md:min-h-[400px]">
            <div class="aspect-video bg-zinc-800 relative overflow-hidden">
                <img src="{{ $heroPost->image_url ?? 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&q=80&w=1200' }}" alt="{{ $heroPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent"></div>
            </div>
            <div class="p-6 md:p-8 mt-auto relative z-10">
                <span class="inline-block bg-accent text-[9px] md:text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-sm mb-3 md:mb-4">{{ $heroPost->category->name }}</span>
                <h2 class="text-xl md:text-3xl font-black text-white leading-tight mb-3 md:mb-4 group-hover:text-secondary transition uppercase tracking-tighter line-clamp-2">{{ $heroPost->title }}</h2>
                <p class="hidden md:block text-white/70 text-sm font-medium max-w-lg mb-6 leading-relaxed line-clamp-2">{{ Str::limit(strip_tags($heroPost->content), 120) }}</p>
                <div class="text-[9px] md:text-[10px] font-bold text-white/40 uppercase tracking-widest flex items-center gap-2">
                    <span>News</span>
                    <span class="w-1 h-1 bg-white/20 rounded-full"></span>
                    <span>{{ $heroPost->published_at ? $heroPost->published_at->diffForHumans() : $heroPost->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </a>
        @else
        <div class="bg-primary rounded-2xl shadow-xl overflow-hidden h-full flex items-center justify-center p-12 text-center border-4 border-dashed border-white/5">
            <span class="text-white/20 font-black uppercase tracking-widest italic">No featured story available</span>
        </div>
        @endif
    </div>

    <!-- Secondary News (Right 4 Columns) -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-6 h-full space-y-6">
            <h3 class="text-[10px] md:text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 border-b border-zinc-50 pb-4">Trending Articles</h3>
            
            @forelse($trendingPosts as $post)
            <a href="{{ route('news.show', $post->slug) }}" class="group cursor-pointer block border-b border-zinc-50 last:border-0 pb-6 last:pb-0">
                <div class="flex gap-4 items-start">
                    <div class="flex-1">
                        <h4 class="text-xs md:text-[13px] font-black text-primary leading-tight group-hover:text-secondary transition uppercase tracking-tighter line-clamp-3">{{ $post->title }}</h4>
                        <span class="text-[8px] md:text-[9px] font-black text-zinc-300 uppercase mt-2 block">{{ $post->category->name }}</span>
                    </div>
                    @if($post->image_url)
                    <div class="w-14 h-10 md:w-16 md:h-12 bg-zinc-100 rounded object-cover overflow-hidden shrink-0">
                        <img src="{{ $post->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    @endif
                </div>
            </a>
            @empty
            <div class="py-8 text-center bg-zinc-50 rounded-xl border-2 border-dashed border-zinc-100">
                <span class="text-[9px] font-black text-zinc-300 uppercase tracking-widest italic">Check back later for updates</span>
            </div>
            @endforelse
            
            <a href="{{ route('news.index') }}" class="block text-center pt-4 text-[9px] md:text-[10px] font-black text-primary hover:text-secondary transition uppercase tracking-widest italic">
                View All News Items →
            </a>
        </div>
    </div>
</div>

@if(count($highlights) > 0)
<!-- Match Highlights Section -->
<div class="col-span-12 mt-12 mb-8 px-4 sm:px-0">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-xl md:text-2xl font-black text-primary uppercase tracking-tighter italic">Match Highlights</h2>
        <a href="{{ route('results') }}" class="text-[10px] font-black text-zinc-400 hover:text-primary transition uppercase tracking-widest">View All Results →</a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($highlights as $match)
        <div @click="videoUrl = getEmbedUrl('{{ $match->highlights_url }}'); videoModalOpen = true" class="group block relative space-y-4 cursor-pointer">
            <div class="aspect-video bg-zinc-800 rounded-2xl overflow-hidden relative shadow-lg">
                <!-- Thumbnail -->
                @if($match->highlights_thumbnail)
                    <img src="{{ $match->highlights_thumbnail }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-80 group-hover:opacity-100" alt="">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-primary">
                        <span class="text-secondary font-black text-4xl italic opacity-10">{{ $match->homeTeam->short_name ?? 'MATCH' }}</span>
                    </div>
                @endif
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-center justify-center">
                    <div class="w-12 h-12 bg-secondary rounded-full flex items-center justify-center shadow-2xl group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6 text-primary fill-current ml-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </div>

                <!-- Scores Overlay -->
                <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if($match->homeTeam->logo_url)
                            <img src="{{ $match->homeTeam->logo_url }}" class="w-5 h-5 object-contain" alt="">
                        @endif
                        <span class="text-[10px] font-black text-white uppercase">{{ $match->homeTeam->short_name ?? substr($match->homeTeam->name, 0, 3) }}</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md px-2 py-0.5 rounded text-[10px] font-black text-white">
                        {{ $match->home_score }} - {{ $match->away_score }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black text-white uppercase">{{ $match->awayTeam->short_name ?? substr($match->awayTeam->name, 0, 3) }}</span>
                        @if($match->awayTeam->logo_url)
                            <img src="{{ $match->awayTeam->logo_url }}" class="w-5 h-5 object-contain" alt="">
                        @endif
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="text-xs font-black text-primary uppercase tracking-tighter line-clamp-2 leading-tight group-hover:text-secondary transition">
                    {{ $match->homeTeam->name }} v {{ $match->awayTeam->name }}
                </h4>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">{{ $match->stage }}</span>
                    <span class="w-1 h-1 bg-zinc-200 rounded-full"></span>
                    <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">{{ $match->match_date->format('j M Y') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@if(count($afconPosts) > 0)
<!-- Africa Cup of Nations Section -->
<div class="col-span-12 mb-12">
    <div class="flex items-center justify-between mb-8 border-b border-zinc-100 pb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-secondary rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-primary uppercase tracking-tighter leading-none">Africa Cup of Nations</h2>
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Latest from AFCON</span>
            </div>
        </div>
        <a href="{{ route('news.index') }}?category=africa-cup-of-nations" class="text-[10px] font-black text-primary uppercase tracking-widest hover:text-secondary transition flex items-center gap-2">
            View All
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($afconPosts as $post)
        <a href="{{ route('news.show', $post->slug) }}" class="group block bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
            <div class="aspect-video relative overflow-hidden">
                <img src="{{ $post->image_url ?? 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&q=80&w=800' }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                
                @if($post->match_id)
                <div class="absolute top-4 left-4">
                    <span class="bg-secondary text-primary text-[8px] font-black px-2 py-1 rounded uppercase tracking-widest shadow-lg">Match Report</span>
                </div>
                @endif
            </div>
            <div class="p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[8px] font-black text-secondary uppercase tracking-widest">{{ $post->category->name }}</span>
                    <span class="w-1 h-1 bg-zinc-200 rounded-full"></span>
                    <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                </div>
                <h3 class="text-sm font-black text-primary uppercase tracking-tighter leading-tight group-hover:text-secondary transition line-clamp-2">
                    {{ $post->title }}
                </h3>
                <p class="text-[11px] text-zinc-500 font-medium mt-3 line-clamp-2 leading-relaxed">
                    {{ Str::limit(strip_tags($post->content), 80) }}
                </p>
                <div class="mt-4 flex items-center gap-2 text-[9px] font-black text-primary uppercase tracking-widest group-hover:gap-3 transition-all">
                    Read Story
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif
@endif
@endsection
