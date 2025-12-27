@extends('layout')

@section('content')
<div class="max-w-[800px] mx-auto space-y-8 md:space-y-12">
    <h2 class="text-2xl md:text-3xl font-black text-primary flex items-center justify-between uppercase tracking-tighter italic">
        Fixtures
    </h2>

    @foreach($fixtures as $date => $matches)
    <div class="space-y-4">
        <h3 class="text-[10px] md:text-[11px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100 pb-2">
            {{ \Carbon\Carbon::parse($date)->format('l j F Y') }}
        </h3>
        
        <div class="space-y-3">
            @foreach($matches as $match)
            <a href="{{ route('match.details', $match->id) }}" class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-zinc-100 hover:border-secondary hover:shadow-md transition-all group cursor-pointer block">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">{{ $match->stage === 'group' ? 'Group ' . $match->group?->name : $match->stage }}</span>
                    <span class="text-[9px] font-black text-primary bg-secondary/20 px-2 py-0.5 rounded uppercase tracking-widest group-hover:bg-secondary/40 transition">{{ $match->venue }}</span>
                </div>
                <div class="flex items-center justify-between gap-3 md:gap-6">
                    <div class="flex-1 flex items-center justify-end gap-2 md:gap-3 font-black text-primary text-xs md:text-base text-right">
                        <span class="truncate">{{ $match->homeTeam->name }}</span>
                        @if($match->homeTeam->logo_url)
                        <img src="{{ $match->homeTeam->logo_url }}" class="w-6 h-6 md:w-8 md:h-8 object-contain shrink-0">
                        @endif
                    </div>
                    
                    <div class="flex flex-col items-center gap-1">
                        @if($match->status === 'live' || $match->started_at)
                        <div class="flex flex-col items-center gap-1">
                            <span class="bg-rose-500 text-white text-[7px] md:text-[8px] font-black px-2 py-0.5 rounded-full animate-pulse uppercase tracking-widest">LIVE</span>
                            <div class="bg-zinc-900 px-3 py-1.5 md:px-4 md:py-2 rounded-lg font-black text-white text-xs md:text-sm shadow-lg flex items-center gap-2">
                                <span>{{ $match->home_score ?? 0 }}</span>
                                <span class="text-zinc-500">-</span>
                                <span>{{ $match->away_score ?? 0 }}</span>
                            </div>
                        </div>
                        @else
                        <div class="bg-zinc-50 border border-zinc-200 px-3 py-1.5 md:px-4 md:py-2 rounded-lg font-black text-primary text-xs md:text-sm shadow-inner group-hover:bg-white transition whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($match->match_date)->format('H:i') }}
                        </div>
                        @endif
                    </div>

                    <div class="flex-1 flex items-center justify-start gap-2 md:gap-3 font-black text-primary text-xs md:text-base">
                        @if($match->awayTeam->logo_url)
                        <img src="{{ $match->awayTeam->logo_url }}" class="w-6 h-6 md:w-8 md:h-8 object-contain shrink-0">
                        @endif
                        <span class="truncate">{{ $match->awayTeam->name }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endforeach

    @if($noveltyFixtures->count() > 0)
    <div class="space-y-8 pt-12 border-t border-zinc-100">
        <div class="text-center space-y-2">
            <h3 class="text-xl md:text-2xl font-black text-accent uppercase italic tracking-tighter">Upcoming Exhibition Matches</h3>
            <p class="text-[9px] md:text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em]">Non-Competitive One-Off Games</p>
        </div>

        <div class="grid gap-6">
            @foreach($noveltyFixtures as $match)
            <a href="{{ route('match.details', $match->id) }}" class="relative overflow-hidden bg-primary border-4 border-white/10 rounded-3xl md:rounded-[2.5rem] p-6 md:p-10 hover:border-secondary transition-all group block shadow-2xl">
                <!-- Dynamic Gradient Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-[#3d195b] via-[#6d28d9] to-[#00ff85] animate-gradient-xy"></div>
                
                <!-- Content Container -->
                <div class="absolute top-0 right-0 p-4 md:p-6">
                    <span class="text-[8px] md:text-[10px] font-black text-primary bg-secondary px-3 py-1 md:px-4 md:py-1.5 rounded-full uppercase tracking-[0.2em] shadow-xl">Novelty Match</span>
                </div>
                
                <div class="flex flex-col items-center justify-between gap-8 md:gap-12 relative z-10">
                    <div class="flex flex-row md:flex-col items-center gap-4 md:gap-6 w-full justify-center">
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-white/20 rounded-full p-3 md:p-4 backdrop-blur-md border border-white/20 group-hover:scale-110 transition duration-500 shadow-lg shrink-0">
                            @if($match->homeTeam->logo_url)
                            <img src="{{ $match->homeTeam->logo_url }}" class="w-full h-full object-contain">
                            @else
                            <div class="w-full h-full flex items-center justify-center font-black text-xl md:text-2xl text-white">
                                {{ substr($match->homeTeam->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <span class="text-xl md:text-2xl font-black text-white uppercase tracking-tighter drop-shadow-lg truncate">{{ $match->homeTeam->name }}</span>
                    </div>

                    <div class="flex flex-col items-center gap-3 md:gap-4">
                        <div class="bg-white/10 backdrop-blur-xl px-6 py-3 md:px-10 md:py-5 rounded-2xl md:rounded-3xl border border-white/30 shadow-2xl">
                            <span class="text-3xl md:text-5xl font-black text-secondary italic tracking-tighter drop-shadow-md">{{ \Carbon\Carbon::parse($match->match_date)->format('H:i') }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-[10px] md:text-[11px] font-black text-white/80 uppercase tracking-[0.3em]">{{ $match->match_date->format('j F Y') }}</span>
                            <span class="text-[8px] md:text-[9px] font-bold text-secondary uppercase tracking-[0.2em] mt-2 bg-primary/40 px-3 py-1 rounded-full">{{ $match->venue }}</span>
                        </div>
                    </div>

                    <div class="flex flex-row-reverse md:flex-col items-center gap-4 md:gap-6 w-full justify-center">
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-white/20 rounded-full p-3 md:p-4 backdrop-blur-md border border-white/20 group-hover:scale-110 transition duration-500 shadow-lg shrink-0">
                            @if($match->awayTeam->logo_url)
                            <img src="{{ $match->awayTeam->logo_url }}" class="w-full h-full object-contain">
                            @else
                            <div class="w-full h-full flex items-center justify-center font-black text-xl md:text-2xl text-white">
                                {{ substr($match->awayTeam->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <span class="text-xl md:text-2xl font-black text-white uppercase tracking-tighter drop-shadow-lg truncate">{{ $match->awayTeam->name }}</span>
                    </div>
                </div>

                <div class="mt-8 md:mt-10 flex justify-center">
                    <span class="text-[9px] md:text-[11px] font-black text-white group-hover:text-secondary transition-all uppercase tracking-[0.25em] bg-white/10 px-6 py-2.5 md:px-8 md:py-3 rounded-full border border-white/10 hover:bg-white/20">Match Tickets & Details →</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($fixtures->count() == 0 && $noveltyFixtures->count() == 0)
    <div class="bg-white rounded-3xl p-12 md:p-20 text-center border-2 border-dashed border-zinc-100">
        <span class="text-zinc-300 font-black uppercase tracking-widest text-[10px] md:text-xs">No upcoming fixtures scheduled.</span>
    </div>
    @endif
</div>
@endsection
