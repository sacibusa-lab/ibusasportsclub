@extends('layout')

@section('content')
<!-- Upcoming Matches Carousel (Full Width) -->
<div class="col-span-12 mb-8">
    <h2 class="text-2xl font-black text-primary uppercase tracking-tighter mb-6">Next Matches</h2>
    <div class="flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory no-scrollbar">
        @forelse($upcomingMatches as $match)
        <div class="flex-none w-[350px] snap-start">
            <div class="bg-white rounded-lg shadow-sm border border-zinc-200 overflow-hidden group hover:shadow-md transition-all duration-300">
                <!-- Header -->
                <div class="px-5 py-3 bg-zinc-50 border-b border-zinc-100 flex justify-between items-center">
                    <span class="text-[10px] font-black text-zinc-800 uppercase tracking-widest">{{ $match->match_date->format('D d.m.Y') }}</span>
                    <span class="text-[10px] font-black text-zinc-800 uppercase tracking-widest">{{ $match->match_date->format('H:i') }}</span>
                </div>

                <!-- Body -->
                <div class="p-5 flex items-center justify-between">
                    <!-- Match Info -->
                    <div class="flex items-center gap-4 flex-1">
                        <div class="text-center w-12">
                            <span class="block text-lg font-black text-zinc-900 uppercase tracking-tighter mb-1">{{ substr(strtoupper($match->homeTeam->short_name ?? substr($match->homeTeam->name, 0, 3)), 0, 3) }}</span>
                            @if($match->homeTeam->logo_url)
                            <img src="{{ $match->homeTeam->logo_url }}" class="w-8 h-8 object-contain mx-auto">
                            @endif
                        </div>
                        
                        <span class="text-xs font-bold text-zinc-400">vs</span>

                        <div class="text-center w-12">
                            @if($match->awayTeam->logo_url)
                            <img src="{{ $match->awayTeam->logo_url }}" class="w-8 h-8 object-contain mx-auto mb-1">
                            @endif
                            <span class="block text-lg font-black text-zinc-900 uppercase tracking-tighter">{{ substr(strtoupper($match->awayTeam->short_name ?? substr($match->awayTeam->name, 0, 3)), 0, 3) }}</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="w-px h-12 bg-zinc-100 mx-4"></div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-2">
                        <button class="flex items-center gap-2 text-[10px] font-bold text-zinc-600 hover:text-primary transition uppercase tracking-wide">
                            <span class="w-3">📊</span> Compare
                        </button>
                        <button class="flex items-center gap-2 text-[10px] font-bold text-zinc-600 hover:text-primary transition uppercase tracking-wide">
                            <span class="w-3">⚽</span> Previous
                        </button>
                        <button class="flex items-center gap-2 text-[10px] font-bold text-zinc-600 hover:text-primary transition uppercase tracking-wide">
                            <span class="w-3">📺</span> TV
                        </button>
                    </div>
                </div>

                <!-- Hashtag -->
                <div class="px-5 pb-4">
                    <span class="text-xs font-bold text-[#8b5cf6] hover:underline cursor-pointer">#{{ str_replace(' ', '', $match->homeTeam->name . $match->awayTeam->name) }}</span>
                </div>

                <!-- Footer Button -->
                <button class="w-full bg-[#ff4b4b] hover:bg-[#ff3333] text-white text-[11px] font-black uppercase tracking-widest py-3 transition text-center">
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

<div class="grid lg:grid-cols-12 gap-6">
    <!-- Main Editorial Hero (Left 8 Columns - Expanded) -->
    <div class="lg:col-span-8">
        @if($heroPost)
        <a href="{{ route('news.show', $heroPost->slug) }}" class="bg-primary rounded-2xl shadow-xl overflow-hidden h-full flex flex-col relative group block min-h-[400px]">
            <div class="aspect-video bg-zinc-800 relative overflow-hidden">
                <img src="{{ $heroPost->image_url ?? 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&q=80&w=1200' }}" alt="{{ $heroPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent"></div>
            </div>
            <div class="p-8 mt-auto relative z-10">
                <span class="inline-block bg-accent text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-sm mb-4">{{ $heroPost->category->name }}</span>
                <h2 class="text-3xl font-black text-white leading-tight mb-4 group-hover:text-secondary transition uppercase tracking-tighter">{{ $heroPost->title }}</h2>
                <p class="text-white/70 text-sm font-medium max-w-lg mb-6 leading-relaxed line-clamp-2">{{ Str::limit(strip_tags($heroPost->content), 120) }}</p>
                <div class="text-[10px] font-bold text-white/40 uppercase tracking-widest flex items-center gap-2">
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
            <h3 class="text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 border-b border-zinc-50 pb-4">Trending Articles</h3>
            
            @forelse($trendingPosts as $post)
            <a href="{{ route('news.show', $post->slug) }}" class="group cursor-pointer block border-b border-zinc-50 last:border-0 pb-6 last:pb-0">
                <div class="flex gap-4 items-start">
                    <div class="flex-1">
                        <h4 class="text-[13px] font-black text-primary leading-tight group-hover:text-secondary transition uppercase tracking-tighter line-clamp-3">{{ $post->title }}</h4>
                        <span class="text-[9px] font-black text-zinc-300 uppercase mt-2 block">{{ $post->category->name }}</span>
                    </div>
                    @if($post->image_url)
                    <div class="w-16 h-12 bg-zinc-100 rounded object-cover overflow-hidden shrink-0">
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
            
            <a href="{{ route('news.index') }}" class="block text-center pt-4 text-[10px] font-black text-primary hover:text-secondary transition uppercase tracking-widest italic">
                View All News Items →
            </a>
        </div>
    </div>
</div>
@endsection
