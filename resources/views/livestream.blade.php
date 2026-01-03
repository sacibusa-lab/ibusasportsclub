@extends('layout')

@section('title', 'Live Match Stream')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    @if($match)
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-xl md:text-2xl font-black text-primary uppercase tracking-tight flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-rose-600 animate-pulse shadow-lg shadow-rose-500/50"></span>
                Live Stream
            </h1>
            <div class="flex items-center gap-2">
                 @if($match->status === 'live')
                <div class="px-3 py-1 bg-rose-600 text-white rounded-lg text-[10px] md:text-xs font-black uppercase tracking-widest animate-pulse shadow-lg">
                    Live Now
                </div>
                @else
                <div class="px-3 py-1 bg-amber-500 text-white rounded-lg text-[10px] md:text-xs font-black uppercase tracking-widest shadow-lg">
                    Upcoming Start
                </div>
                @endif
            </div>
        </div>

        <div class="bg-black rounded-3xl overflow-hidden shadow-2xl aspect-video relative border border-zinc-900 group">
            @if($match->stream_url)
                @if(Str::contains($match->stream_url, ['youtube.com', 'youtu.be']))
                    @php
                        $url = $match->stream_url;
                        if(Str::contains($url, 'watch?v=')) {
                            parse_str(parse_url($url, PHP_URL_QUERY), $params);
                            $videoId = $params['v'] ?? null;
                        } else {
                            $videoId = last(explode('/', $url));
                        }
                    @endphp
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @elseif(Str::contains($match->stream_url, 'twitch.tv'))
                     @php
                        $channel = last(explode('/', $match->stream_url));
                    @endphp
                    <iframe class="w-full h-full" src="https://player.twitch.tv/?channel={{ $channel }}&parent={{ request()->getHost() }}" frameborder="0" allowfullscreen="true" scrolling="no"></iframe>
                @else
                    <!-- Generic Embed / Direct Video -->
                    <iframe class="w-full h-full" src="{{ $match->stream_url }}" frameborder="0" allowfullscreen></iframe>
                @endif
            @else
                <div class="absolute inset-0 flex flex-col items-center justify-center text-white/30 bg-zinc-900">
                    <svg class="w-24 h-24 mb-6 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <div class="text-2xl font-black uppercase tracking-widest opacity-40">Stream Offline</div>
                    <div class="text-sm mt-2 opacity-30">No active feed for this match</div>
                </div>
            @endif
        </div>
        
        <!-- Match Info Card -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-zinc-100 shadow-sm relative overflow-hidden">
             <!-- Background Pattern -->
            <div class="absolute top-0 right-0 p-8 opacity-5">
                <svg class="w-64 h-64 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 md:gap-16">
                <div class="flex-1 flex items-center justify-end gap-6 w-full md:w-auto">
                    <div class="text-right hidden md:block">
                        <h2 class="text-xl md:text-2xl font-black text-primary uppercase leading-none">{{ $match->homeTeam->name }}</h2>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-1">Home Team</p>
                    </div>
                    <img src="{{ $match->homeTeam->logo_url }}" class="w-16 h-16 md:w-24 md:h-24 object-contain drop-shadow-xl">
                    <div class="text-left md:hidden">
                        <h2 class="text-xl font-black text-primary uppercase leading-none">{{ $match->homeTeam->name }}</h2>
                    </div>
                </div>

                <div class="flex flex-col items-center shrink-0">
                    <div class="bg-zinc-50 px-8 py-3 rounded-2xl border border-zinc-100 mb-2">
                        <span class="text-4xl md:text-5xl font-black text-primary tracking-tighter">
                            {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                        </span>
                    </div>
                    @if($match->status === 'live')
                    <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest animate-pulse">
                        {{ $match->matchEvents->last()->minute ?? '1st' }}' Minute
                    </span>
                    @else
                    <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">
                        {{ $match->match_date->format('H:i') }} Kickoff
                    </span>
                    @endif
                </div>

                <div class="flex-1 flex items-center justify-start gap-6 w-full md:w-auto flex-row-reverse md:flex-row">
                    <div class="text-left hidden md:block">
                        <h2 class="text-xl md:text-2xl font-black text-primary uppercase leading-none">{{ $match->awayTeam->name }}</h2>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-1">Away Team</p>
                    </div>
                    <img src="{{ $match->awayTeam->logo_url }}" class="w-16 h-16 md:w-24 md:h-24 object-contain drop-shadow-xl">
                     <div class="text-right md:hidden">
                        <h2 class="text-xl font-black text-primary uppercase leading-none">{{ $match->awayTeam->name }}</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Content (Lineups, etc.) Link -->
        <div class="text-center">
            <a href="{{ route('match.details', $match->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-zinc-200 rounded-xl text-xs font-black text-primary uppercase tracking-widest hover:bg-zinc-50 transition shadow-sm hover:shadow-md">
                View Full Match Stats & Lineups
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
    @else
    <div class="min-h-[60vh] flex flex-col items-center justify-center text-center p-8">
        <div class="w-24 h-24 bg-zinc-100 rounded-full flex items-center justify-center mb-8 text-zinc-300">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        </div>
        <h1 class="text-3xl font-black text-primary uppercase tracking-tight mb-4">No Live Stream</h1>
        <p class="text-zinc-400 font-medium max-w-md mx-auto mb-8">There are currently no matches being broadcast live. Check back later for upcoming fixtures.</p>
        
        <a href="{{ route('fixtures') }}" class="px-8 py-4 bg-primary text-secondary rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-light transition shadow-lg">
            View Upcoming Fixtures
        </a>
    </div>
    @endif

</div>
@endsection
