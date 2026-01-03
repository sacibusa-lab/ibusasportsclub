@extends('admin.layout')

@section('title', 'Livestream Watch')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    @if($match)
    <div class="bg-black/90 rounded-3xl overflow-hidden shadow-2xl aspect-video relative border border-zinc-800">
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
            <div class="absolute inset-0 flex flex-col items-center justify-center text-white/30">
                <svg class="w-20 h-20 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <div class="text-xl font-black uppercase tracking-widest">No Stream URL Configured</div>
                <div class="text-sm mt-2">Edit the fixture to add a stream link</div>
            </div>
        @endif

        <!-- Overlay Info (Optional, only provided if generic/direct or if desired on top) -->
        <div class="absolute top-0 left-0 w-full p-6 bg-gradient-to-b from-black/80 to-transparent pointer-events-none">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    @if($match->status === 'live')
                    <div class="px-3 py-1 bg-rose-600 text-white rounded-lg text-xs font-black uppercase tracking-widest animate-pulse shadow-lg">
                        Live Now
                    </div>
                    @endif
                    <div class="text-white">
                        <div class="text-lg font-black uppercase tracking-tight shadow-black drop-shadow-md">
                            {{ $match->homeTeam->name }} <span class="text-white/60 mx-2">vs</span> {{ $match->awayTeam->name }}
                        </div>
                        <div class="text-xs font-bold text-white/60 uppercase tracking-widest">
                            {{ $match->competition->name ?? 'Tournament' }} • {{ $match->match_date->format('d M, H:i') }}
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 pointer-events-auto">
                    <a href="{{ route('admin.live-console.control', $match->id) }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold uppercase tracking-widest backdrop-blur-sm transition border border-white/10">
                        Open Control Console
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm">
            <h3 class="text-xs font-black text-zinc-400 uppercase tracking-widest mb-4">Match Status</h3>
            <div class="flex items-center gap-4 mb-4">
                <div class="flex-1 text-center">
                    <img src="{{ $match->homeTeam->logo_url }}" class="w-16 h-16 mx-auto mb-2 object-contain">
                    <div class="font-black text-primary text-sm uppercase">{{ $match->homeTeam->name }}</div>
                </div>
                <div class="text-3xl font-black text-zinc-300">
                    {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                </div>
                <div class="flex-1 text-center">
                    <img src="{{ $match->awayTeam->logo_url }}" class="w-16 h-16 mx-auto mb-2 object-contain">
                    <div class="font-black text-primary text-sm uppercase">{{ $match->awayTeam->name }}</div>
                </div>
            </div>
            <div class="text-center">
                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $match->status === 'live' ? 'bg-rose-100 text-rose-600' : 'bg-zinc-100 text-zinc-500' }}">
                    {{ $match->status }}
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm md:col-span-2">
            <form action="{{ route('admin.matches.stream.update', $match->id) }}" method="POST" class="flex items-end gap-4">
                @csrf
                <div class="flex-1 space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest">Update Stream URL</label>
                    <input type="text" name="stream_url" value="{{ $match->stream_url }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" placeholder="Paste YouTube/Twitch URL here to update live...">
                </div>
                <button type="submit" class="bg-primary text-secondary font-black py-4 px-8 rounded-xl hover:bg-primary-light transition uppercase tracking-widest text-xs shadow-lg h-[50px]">
                    Update
                </button>
            </form>
             @if(session('success'))
                <div class="mt-4 bg-emerald-50 border border-emerald-100 p-3 rounded-xl flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-emerald-700 text-[10px] font-bold uppercase tracking-widest">{{ session('success') }}</span>
                </div>
            @endif
        </div>
    </div>

    @else
    <div class="bg-white rounded-3xl p-12 text-center border border-zinc-100 shadow-sm">
        <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-6 text-zinc-300">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-lg font-black text-primary uppercase mb-2">No Active Stream</h3>
        <p class="text-zinc-400 text-sm font-bold uppercase tracking-widest">There are no live or upcoming matches with streams available right now.</p>
    </div>
    @endif

</div>
@endsection
