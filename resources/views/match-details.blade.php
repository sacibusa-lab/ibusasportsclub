@extends('layout')

@section('title', $match->homeTeam->name . ' vs ' . $match->awayTeam->name)

@section('content')
<div x-data="{ tab: 'recap' }" class="max-w-6xl mx-auto space-y-6 md:space-y-8 pb-24">
    <!-- Breadcrumbs -->
    <nav class="flex text-[9px] md:text-[10px] font-bold text-zinc-400 uppercase tracking-widest gap-2 overflow-x-auto no-scrollbar whitespace-nowrap px-4 md:px-0">
        <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
        <span>/</span>
        <a href="{{ route('results') }}" class="hover:text-primary transition">Results</a>
        <span>/</span>
        <span class="text-primary">Match Details</span>
    </nav>

    <!-- Premium Dynamic Match Header Card -->
    <div class="rounded-3xl md:rounded-[2.5rem] shadow-2xl overflow-hidden relative border-2 md:border-4 border-white/20 mx-2 md:mx-0">
        <!-- Dynamic Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#3d195b] via-[#6d28d9] to-[#00ff85] animate-gradient-xy"></div>
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        
        <!-- Content Container -->
        <div class="relative z-10">
            <div class="flex flex-col md:grid md:grid-cols-3 items-center py-8 md:py-10 px-4 md:px-8 gap-8 md:gap-0">
                <!-- Home Team -->
                <div class="flex flex-row md:flex-col items-center gap-4 group w-full md:w-auto justify-center">
                    <div class="relative order-2 md:order-1">
                        <div class="absolute -inset-3 md:-inset-4 bg-white/20 rounded-full blur-xl group-hover:bg-white/30 transition duration-500"></div>
                        @if($match->homeTeam->logo_url)
                        <img src="{{ $match->homeTeam->logo_url }}" class="w-16 h-16 md:w-24 md:h-24 object-contain relative z-10 drop-shadow-[0_10px_10px_rgba(0,0,0,0.3)] group-hover:scale-110 transition duration-500">
                        @else
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center font-black text-2xl md:text-4xl text-white relative z-10 border-2 border-white/30 group-hover:border-white transition duration-500">
                            {{ substr($match->homeTeam->name, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <h2 class="text-base md:text-xl font-black text-white uppercase tracking-tighter text-center leading-tight drop-shadow-lg max-w-[120px] md:max-w-[140px] order-1 md:order-2">{{ $match->homeTeam->name }}</h2>
                </div>

                <!-- Score / VS Center -->
                <div class="flex flex-col items-center justify-center md:order-2">
                    <div class="bg-white/10 backdrop-blur-2xl rounded-2xl md:rounded-3xl p-4 md:p-8 border border-white/30 shadow-2xl min-w-[140px] md:min-w-[180px]">
                        @if($match->status === 'finished')
                        <div class="flex flex-col items-center">
                            <div class="text-4xl md:text-6xl font-black text-white tracking-[0.2em] md:tracking-widest flex items-center gap-3 md:gap-6 drop-shadow-[0_5px_15px_rgba(0,0,0,0.5)]">
                                <span class="text-secondary">{{ $match->home_score }}</span>
                                <span class="text-white/40 text-2xl md:text-4xl">:</span>
                                <span class="text-accent">{{ $match->away_score }}</span>
                            </div>
                            <div class="mt-3 md:mt-4 px-3 py-1 md:px-4 md:py-1.5 bg-secondary text-primary rounded-full text-[8px] md:text-[9px] font-black uppercase tracking-widest shadow-lg shadow-secondary/20">Full Time</div>
                        </div>
                        @else
                        <div class="flex flex-col items-center">
                            <div class="text-3xl md:text-5xl font-black text-white tracking-tighter italic drop-shadow-lg">VS</div>
                            <div class="mt-3 md:mt-4 px-3 py-1 md:px-4 md:py-1.5 bg-white text-primary rounded-full text-[8px] md:text-[9px] font-black uppercase tracking-widest shadow-lg">{{ \Carbon\Carbon::parse($match->match_date)->format('H:i') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Away Team -->
                <div class="flex flex-row-reverse md:flex-col items-center gap-4 group w-full md:w-auto justify-center md:order-3">
                    <div class="relative order-2 md:order-1">
                        <div class="absolute -inset-3 md:-inset-4 bg-white/20 rounded-full blur-xl group-hover:bg-white/30 transition duration-500"></div>
                        @if($match->awayTeam->logo_url)
                        <img src="{{ $match->awayTeam->logo_url }}" class="w-16 h-16 md:w-24 md:h-24 object-contain relative z-10 drop-shadow-[0_10px_10px_rgba(0,0,0,0.3)] group-hover:scale-110 transition duration-500">
                        @else
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center font-black text-2xl md:text-4xl text-white relative z-10 border-2 border-white/30 group-hover:border-white transition duration-500">
                            {{ substr($match->awayTeam->name, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <h2 class="text-base md:text-xl font-black text-white uppercase tracking-tighter text-center leading-tight drop-shadow-lg max-w-[120px] md:max-w-[140px] order-1 md:order-2">{{ $match->awayTeam->name }}</h2>
                </div>
            </div>

            <!-- Meta Bar Overlay -->
            <div class="bg-indigo-950/40 backdrop-blur-xl border-t border-white/10 px-4 md:px-8 py-3 flex flex-col md:flex-row items-center justify-between gap-2 md:gap-0">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></div>
                    <span class="text-[9px] md:text-[10px] font-black text-white uppercase tracking-[0.2em] md:tracking-[0.25em]">{{ $match->venue }}</span>
                </div>
                <div class="flex items-center gap-4 md:gap-6">
                    <span class="text-[9px] md:text-[10px] font-black text-white/70 uppercase tracking-[0.2em] md:tracking-[0.25em]">{{ \Carbon\Carbon::parse($match->match_date)->format('l j F Y') }}</span>
                    @if($match->stage === 'novelty')
                    <span class="text-[8px] font-black text-primary bg-secondary px-2 md:px-3 py-0.5 md:py-1 rounded-sm uppercase tracking-widest whitespace-nowrap">Exhibition</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 bg-white p-1 rounded-2xl border border-zinc-100 shadow-sm overflow-x-auto sticky top-4 z-20">
        <button @click="tab = 'recap'" :class="tab === 'recap' ? 'bg-primary text-secondary shadow-md' : 'text-zinc-400 hover:bg-zinc-50'" class="flex-1 py-3 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest transition">Recap</button>
        <button @click="tab = 'lineups'" :class="tab === 'lineups' ? 'bg-primary text-secondary shadow-md' : 'text-zinc-400 hover:bg-zinc-50'" class="flex-1 py-3 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest transition">Lineups</button>
        <button @click="tab = 'stats'" :class="tab === 'stats' ? 'bg-primary text-secondary shadow-md' : 'text-zinc-400 hover:bg-zinc-50'" class="flex-1 py-3 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest transition">Stats</button>
        <button @click="tab = 'info'" :class="tab === 'info' ? 'bg-primary text-secondary shadow-md' : 'text-zinc-400 hover:bg-zinc-50'" class="flex-1 py-3 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest transition">Info</button>
    </div>

    <!-- RECAP TAB -->
    <div x-show="tab === 'recap'" class="space-y-6 md:space-y-8">

    <!-- Timeline & Events -->
    @if($match->status === 'finished')
    <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-4 md:p-8">
        @forelse($match->matchEvents as $event)
        <div class="grid grid-cols-[1fr,auto,1fr] gap-2 md:gap-4 items-center mb-6 md:mb-8 last:mb-0 relative group">
            <!-- Home Team Events (Left) -->
            <div class="flex justify-end items-center gap-2 md:gap-3 text-right">
                @if($event->team_id == $match->home_team_id)
                    @if($event->event_type === 'goal' || $event->event_type === 'yellow_card' || $event->event_type === 'red_card' || $event->event_type === 'penalty')
                        <div class="flex flex-col items-end">
                            <span class="font-bold text-primary text-xs md:text-sm leading-tight">{{ $event->player_name }}</span>
                            @if($event->assistant)
                            <span class="text-[8px] md:text-[10px] text-zinc-400 font-bold uppercase tracking-wide">{{ $event->assistant->name }} (Assist)</span>
                            @endif
                            @if($event->event_type === 'penalty')
                            <span class="text-[8px] md:text-[10px] text-zinc-400 font-black uppercase tracking-widest">(Penalty)</span>
                            @endif
                        </div>
                        
                        <div class="w-5 h-5 md:w-6 md:h-6 flex items-center justify-center">
                            @if($event->event_type === 'goal' || $event->event_type === 'penalty')
                                <span class="text-base md:text-lg">⚽</span>
                            @elseif($event->event_type === 'yellow_card')
                                <div class="w-3 md:w-4 h-4 md:h-5 bg-yellow-400 rounded-sm shadow-sm rotate-6"></div>
                            @elseif($event->event_type === 'red_card')
                                <div class="w-3 md:w-4 h-4 md:h-5 bg-red-600 rounded-sm shadow-sm rotate-6"></div>
                            @endif
                        </div>
                    @elseif($event->event_type === 'sub_on')
                        <div class="flex flex-col items-end">
                            <div class="flex items-center gap-2">
                                 <span class="text-[10px] text-emerald-500 font-bold">IN</span>
                                 <span class="font-bold text-primary text-xs md:text-sm leading-tight">{{ $event->player_name }}</span>
                                 <div class="w-4 h-4 md:w-5 md:h-5 bg-emerald-500 rounded-full flex items-center justify-center text-white text-[10px]">⬆</div>
                            </div>
                            @if($event->relatedPlayer)
                            <div class="flex items-center gap-2 mt-0.5">
                                 <span class="text-[9px] text-zinc-400 font-bold italic">{{ $event->relatedPlayer->name }}</span>
                                 <span class="text-[9px] text-rose-500 font-bold">OUT</span>
                                 <div class="w-4 h-4 md:w-5 md:h-5 bg-rose-500 rounded-full flex items-center justify-center text-white text-[10px]">⬇</div>
                            </div>
                            @endif
                        </div>
                    @elseif($event->event_type === 'sub_off' && !$match->matchEvents->where('event_type', 'sub_on')->where('minute', $event->minute)->where('team_id', $event->team_id)->first())
                        <div class="flex flex-col items-end">
                             <div class="flex items-center gap-2">
                                 <span class="font-bold text-primary text-xs md:text-sm leading-tight">{{ $event->player_name }}</span>
                                 <span class="text-[10px] text-rose-500 font-bold">OUT</span>
                                 <div class="w-4 h-4 md:w-5 md:h-5 bg-rose-500 rounded-full flex items-center justify-center text-white text-[10px]">⬇</div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Minute (Center) -->
            <div class="w-8 md:w-10 flex justify-center">
                <span class="text-[10px] md:text-xs font-black text-zinc-300 group-hover:text-primary transition">{{ $event->minute }}'</span>
            </div>

            <!-- Away Team Events (Right) -->
            <div class="flex justify-start items-center gap-2 md:gap-3 text-left">
                @if($event->team_id == $match->away_team_id)
                    @if($event->event_type === 'goal' || $event->event_type === 'yellow_card' || $event->event_type === 'red_card' || $event->event_type === 'penalty')
                        <div class="flex items-center gap-2 md:gap-3">
                            <div class="w-5 h-5 md:w-6 md:h-6 flex items-center justify-center">
                                @if($event->event_type === 'goal' || $event->event_type === 'penalty')
                                    <span class="text-base md:text-lg">⚽</span>
                                @elseif($event->event_type === 'yellow_card')
                                    <div class="w-3 md:w-4 h-4 md:h-5 bg-yellow-400 rounded-sm shadow-sm -rotate-6"></div>
                                @elseif($event->event_type === 'red_card')
                                    <div class="w-3 md:w-4 h-4 md:h-5 bg-red-600 rounded-sm shadow-sm -rotate-6"></div>
                                @endif
                            </div>
                            <div class="flex flex-col items-start">
                                <span class="font-bold text-primary text-xs md:text-sm leading-tight">{{ $event->player_name }}</span>
                                @if($event->assistant)
                                <span class="text-[8px] md:text-[10px] text-zinc-400 font-bold uppercase tracking-wide">{{ $event->assistant->name }} (Assist)</span>
                                @endif
                                @if($event->event_type === 'penalty')
                                <span class="text-[8px] md:text-[10px] text-zinc-400 font-black uppercase tracking-widest">(Penalty)</span>
                                @endif
                            </div>
                        </div>
                    @elseif($event->event_type === 'sub_on')
                        <div class="flex flex-col items-start">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 md:w-5 md:h-5 bg-emerald-500 rounded-full flex items-center justify-center text-white text-[10px]">⬆</div>
                                <span class="font-bold text-primary text-xs md:text-sm leading-tight">{{ $event->player_name }}</span>
                                <span class="text-[10px] text-emerald-500 font-bold">IN</span>
                            </div>
                            @if($event->relatedPlayer)
                            <div class="flex items-center gap-2 mt-0.5">
                                <div class="w-4 h-4 md:w-5 md:h-5 bg-rose-500 rounded-full flex items-center justify-center text-white text-[10px]">⬇</div>
                                <span class="text-[9px] text-rose-500 font-bold">OUT</span>
                                <span class="text-[9px] text-zinc-400 font-bold italic">{{ $event->relatedPlayer->name }}</span>
                            </div>
                            @endif
                        </div>
                    @elseif($event->event_type === 'sub_off' && !$match->matchEvents->where('event_type', 'sub_on')->where('minute', $event->minute)->where('team_id', $event->team_id)->first())
                        <div class="flex flex-col items-start">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 md:w-5 md:h-5 bg-rose-500 rounded-full flex items-center justify-center text-white text-[10px]">⬇</div>
                                <span class="text-[10px] text-rose-500 font-bold">OUT</span>
                                <span class="font-bold text-primary text-xs md:text-sm leading-tight">{{ $event->player_name }}</span>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-start">
                            <span class="font-bold text-primary text-xs md:text-sm leading-tight">{{ $event->player_name }}</span>
                            @if($event->assistant)
                            <span class="text-[8px] md:text-[10px] text-zinc-400 font-bold uppercase tracking-wide">{{ $event->assistant->name }} (Assist)</span>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <span class="text-[10px] font-black text-zinc-300 uppercase italic tracking-widest">No detailed events recorded.</span>
        </div>
        @endforelse

        @if($match->motm_player_id)
        <div class="mt-8 md:mt-12 pt-8 md:pt-12 border-t border-zinc-100 flex flex-col items-center text-center">
            <span class="text-[9px] md:text-[10px] font-black text-secondary uppercase tracking-widest bg-primary px-3 py-1 rounded-full mb-4 shadow-lg border-2 border-primary ring-2 ring-secondary/50">Man of the Match</span>
            @if($match->motmPlayer->image_url)
            <img src="{{ $match->motmPlayer->image_url }}" class="w-16 h-16 md:w-20 md:h-20 rounded-full object-cover border-2 md:border-4 border-white shadow-lg mb-3">
            @endif
            <h3 class="text-lg md:text-xl font-black text-primary uppercase italic tracking-tighter leading-none">{{ $match->motmPlayer->name }}</h3>
            <span class="text-[10px] md:text-xs font-bold text-zinc-400 mt-1 uppercase tracking-wide">{{ $match->motmPlayer->team->name }}</span>
        </div>
        @endif
    </div>
    @endif
    </div>

    <!-- LINEUPS TAB -->
    <div x-show="tab === 'lineups'" x-cloak>
        @php
            $eventsByPlayer = collect();
            foreach($match->matchEvents as $event) {
                if($event->player_id) {
                    if(!$eventsByPlayer->has($event->player_id)) $eventsByPlayer->put($event->player_id, collect());
                    $eventsByPlayer->get($event->player_id)->push($event);
                }
                
                // If it's a substitution, the related player (OUT player) also needs a virtual event
                if($event->event_type === 'sub_on' && $event->related_player_id) {
                    if(!$eventsByPlayer->has($event->related_player_id)) $eventsByPlayer->put($event->related_player_id, collect());
                    
                    // Create a virtual sub_off event for the player leaving
                    $virtualSubOff = (object)[
                        'event_type' => 'sub_off',
                        'minute' => $event->minute,
                        'player_id' => $event->related_player_id,
                        'match_id' => $event->match_id
                    ];
                    $eventsByPlayer->get($event->related_player_id)->push($virtualSubOff);
                }
            }

            $homeStartingXI = $match->lineups->where('pivot.team_id', $match->home_team_id)->where('pivot.is_substitute', false);
            $awayStartingXI = $match->lineups->where('pivot.team_id', $match->away_team_id)->where('pivot.is_substitute', false);
            $homeSubs = $match->lineups->where('pivot.team_id', $match->home_team_id)->where('pivot.is_substitute', true);
            $awaySubs = $match->lineups->where('pivot.team_id', $match->away_team_id)->where('pivot.is_substitute', true);
        @endphp

        <div class="space-y-8 md:space-y-12">
            <!-- Starting XI Section -->
            <div class="space-y-6 md:space-y-8">
                <div class="relative group">
                    <!-- Scroll Indicator Mobile -->
                    <div class="md:hidden absolute -top-4 right-0 flex items-center gap-1 text-[8px] font-black text-zinc-300 uppercase tracking-widest z-30">
                        <span> formación scroll</span>
                        <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>

                    <!-- Row-Based Green Horizontal Pitch -->
                <div class="relative bg-white border border-zinc-200 rounded-3xl md:rounded-[1.5rem] overflow-x-auto no-scrollbar shadow-2xl mb-8 md:mb-12 p-2 md:p-4">
                    <div class="relative aspect-[4/3] md:aspect-[16/10] min-w-[500px] md:min-w-0 bg-emerald-600 rounded-2xl md:rounded-[2rem] border-2 md:border-4 border-emerald-500 overflow-hidden shadow-inner">
                        <!-- Field Markings -->
                        <div class="absolute inset-0 pointer-events-none opacity-20">
                            <div class="absolute inset-2 md:inset-4 border-2 border-white rounded-xl md:rounded-2xl"></div>
                            <div class="absolute top-2 bottom-2 md:top-4 md:bottom-4 left-1/2 w-0.5 bg-white"></div>
                            <div class="absolute top-1/2 left-1/2 w-32 h-32 md:w-48 md:h-48 border-2 border-white rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
                            <div class="absolute top-1/4 bottom-1/4 left-2 md:left-4 w-16 md:w-28 border-2 border-l-0 border-white rounded-r-lg md:rounded-r-xl"></div>
                            <div class="absolute top-1/4 bottom-1/4 right-2 md:right-4 w-16 md:w-28 border-2 border-r-0 border-white rounded-l-lg md:rounded-l-xl"></div>
                        </div>

                        <!-- Team Headers -->
                        <div class="absolute top-4 md:top-6 left-4 md:left-6 right-4 md:right-6 flex justify-between items-start pointer-events-none z-20">
                            <div class="flex items-center gap-2 md:gap-3">
                                <img src="{{ $match->homeTeam->logo_url }}" class="w-8 h-8 md:w-12 md:h-12 object-contain drop-shadow-lg">
                                <div class="bg-white/90 backdrop-blur px-2 py-1 md:px-3 md:py-1.5 rounded-lg md:rounded-xl border border-white/20 shadow-md">
                                    <h3 class="text-[9px] md:text-[11px] font-black text-indigo-900 uppercase leading-tight">{{ $match->homeTeam->name }}</h3>
                                    <p class="text-[7px] md:text-[9px] font-bold text-indigo-400 mt-0.5">FORMATION 4-3-3</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 md:gap-3 text-right">
                                <div class="bg-white/90 backdrop-blur px-2 py-1 md:px-3 md:py-1.5 rounded-lg md:rounded-xl border border-white/20 shadow-md">
                                    <h3 class="text-[9px] md:text-[11px] font-black text-rose-900 uppercase leading-tight">{{ $match->awayTeam->name }}</h3>
                                    <p class="text-[7px] md:text-[9px] font-bold text-rose-400 mt-0.5">FORMATION 4-2-3-1</p>
                                </div>
                                <img src="{{ $match->awayTeam->logo_url }}" class="w-8 h-8 md:w-12 md:h-12 object-contain drop-shadow-lg">
                            </div>
                        </div>

                        <!-- Players -->
                        @foreach($match->lineups as $player)
                            @if(!$player->pivot->is_substitute && $player->pivot->position_x && $player->pivot->position_y)
                            <div class="absolute transform -translate-x-1/2 -translate-y-1/2 z-10"
                                    style="left: {{ $player->pivot->position_x }}%; top: {{ $player->pivot->position_y }}%;">
                                
                                <div class="flex flex-col items-center group">
                                    <!-- Circular Headshot -->
                                    <div class="relative w-10 h-10 md:w-14 md:h-14 mb-1">
                                        <div class="w-full h-full rounded-full bg-white border-2 {{ $player->pivot->team_id == $match->home_team_id ? 'border-primary' : 'border-rose-500' }} overflow-hidden shadow-lg group-hover:scale-110 transition-transform shadow-black/30">
                                            @if($player->image_url)
                                                <img src="{{ $player->image_url }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-zinc-50">
                                                    <span class="text-[8px] md:text-[10px] font-black text-zinc-300">{{ substr($player->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Event Badges -->
                                        @php $playerEvents = collect($eventsByPlayer->get($player->id, [])); @endphp
                                        <div class="absolute -bottom-1 -right-1 flex flex-col gap-1 items-end z-20">
                                            @if($playerEvents->whereIn('event_type', ['goal', 'penalty'])->count() > 0)
                                                <div class="w-4 h-4 md:w-5 md:h-5 bg-white rounded-full flex items-center justify-center shadow-md border border-zinc-100 mb-0.5">
                                                    <span class="text-[8px] md:text-[9px]">⚽</span>
                                                    @if($playerEvents->whereIn('event_type', ['goal', 'penalty'])->count() > 1)
                                                        <span class="absolute -top-1 -right-1 bg-primary text-white text-[6px] w-2.5 h-2.5 rounded-full flex items-center justify-center border border-white font-black">{{ $playerEvents->whereIn('event_type', ['goal', 'penalty'])->count() }}</span>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="flex flex-col gap-0.5 items-end">
                                                @foreach($playerEvents->where('event_type', 'yellow_card') as $card)
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[7px] md:text-[8px] font-black text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]">{{ $card->minute }}'</span>
                                                        <div class="w-2 h-3 md:w-2.5 md:h-3.5 bg-yellow-400 rounded-sm shadow-md border border-white/50"></div>
                                                    </div>
                                                @endforeach

                                                @foreach($playerEvents->where('event_type', 'red_card') as $card)
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[7px] md:text-[8px] font-black text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]">{{ $card->minute }}'</span>
                                                        <div class="w-2 h-3 md:w-2.5 md:h-3.5 bg-red-600 rounded-sm shadow-md border border-white/50"></div>
                                                    </div>
                                                @endforeach

                                                @foreach($playerEvents->where('event_type', 'sub_off') as $sub)
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[7px] md:text-[8px] font-black text-rose-200 drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]">{{ $sub->minute }}'</span>
                                                        <div class="w-3.5 h-3.5 md:w-4 md:h-4 bg-rose-500 rounded-full flex items-center justify-center text-white text-[8px] md:text-[9px] shadow-md border border-white/30 font-black">⬇</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Label -->
                                    <div class="text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="text-[7px] md:text-[8px] font-black text-white/60 leading-none">#{{ $player->pivot->shirt_number ?? $player->shirt_number }}</span>
                                            <span class="text-[8px] md:text-[10px] font-black text-white uppercase tracking-tighter drop-shadow-md">{{ \Illuminate\Support\Str::afterLast($player->name, ' ') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach

                        <!-- Managers Footer -->
                        <div class="absolute bottom-4 md:bottom-6 left-6 md:left-10 right-6 md:right-10 flex justify-between items-end pointer-events-none z-20">
                            <div class="bg-white/10 backdrop-blur px-2 py-1 md:px-3 md:py-1 rounded-lg border border-white/10">
                                <span class="text-[7px] md:text-[8px] font-bold text-white/50 uppercase tracking-[0.2em] block mb-0.5">Manager</span>
                                <span class="text-[9px] md:text-[10px] font-black text-white uppercase">{{ $match->homeTeam->manager ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-white/10 backdrop-blur px-2 py-1 md:px-3 md:py-1 rounded-lg border border-white/10 text-right">
                                <span class="text-[7px] md:text-[8px] font-bold text-white/50 uppercase tracking-[0.2em] block mb-0.5">Manager</span>
                                <span class="text-[9px] md:text-[10px] font-black text-white uppercase">{{ $match->awayTeam->manager ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Starting XI Lists -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
                    <!-- Home Starting XI -->
                    <div class="bg-white p-6 md:p-8 rounded-3xl md:rounded-[2rem] border border-zinc-100 shadow-sm">
                        <h3 class="text-[10px] font-black text-primary uppercase tracking-widest flex items-center justify-between mb-4 md:mb-6">
                            <span>{{ $match->homeTeam->name }} XI</span>
                            <span class="text-zinc-300">XI</span>
                        </h3>
                        <div class="space-y-3 md:space-y-4">
                            @forelse($homeStartingXI as $player)
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black text-zinc-300 w-4">#{{ $player->pivot->shirt_number ?? '-' }}</span>
                                        <span class="text-xs font-bold text-primary group-hover:text-primary-light transition">{{ $player->name }}</span>
                                        @if($player->pivot->is_captain) <span class="text-[7px] bg-yellow-400 text-yellow-900 px-1 rounded-sm font-black ml-1">C</span> @endif
                                    </div>
                                    <div class="flex items-center gap-1.5 h-4">
                                        @foreach($eventsByPlayer->get($player->id, []) as $event)
                                            @if($event->event_type == 'goal' || $event->event_type == 'penalty')
                                                <span class="text-[10px]" title="Goal {{ $event->minute }}'">⚽</span>
                                            @elseif($event->event_type == 'yellow_card')
                                                <div class="w-1.5 h-2.5 bg-yellow-400 rounded-sm" title="Yellow Card {{ $event->minute }}'"></div>
                                            @elseif($event->event_type == 'red_card')
                                                <div class="w-1.5 h-2.5 bg-red-600 rounded-sm" title="Red Card {{ $event->minute }}'"></div>
                                            @elseif($event->event_type == 'sub_off')
                                                <div class="flex items-center justify-center w-3 h-3 md:w-3.5 md:h-3.5 bg-rose-500 rounded-full text-white text-[7px] md:text-[8px] font-black shadow-sm" title="Off {{ $event->minute }}'">⬇</div>
                                            @elseif($event->event_type == 'sub_on')
                                                <div class="flex items-center justify-center w-3 h-3 md:w-3.5 md:h-3.5 bg-emerald-500 rounded-full text-white text-[7px] md:text-[8px] font-black shadow-sm" title="On {{ $event->minute }}'">➜</div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-[10px] text-zinc-300 italic">No starting XI recorded.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Away Starting XI -->
                    <div class="bg-white p-6 md:p-8 rounded-3xl md:rounded-[2rem] border border-zinc-100 shadow-sm">
                        <h3 class="text-[10px] font-black text-rose-600 uppercase tracking-widest flex items-center justify-between mb-4 md:mb-6">
                            <span>{{ $match->awayTeam->name }} XI</span>
                            <span class="text-zinc-300">XI</span>
                        </h3>
                        <div class="space-y-3 md:space-y-4">
                            @forelse($awayStartingXI as $player)
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black text-zinc-300 w-4">#{{ $player->pivot->shirt_number ?? '-' }}</span>
                                        <span class="text-xs font-bold text-primary group-hover:text-primary-light transition">{{ $player->name }}</span>
                                        @if($player->pivot->is_captain) <span class="text-[7px] bg-yellow-400 text-yellow-900 px-1 rounded-sm font-black ml-1">C</span> @endif
                                    </div>
                                    <div class="flex items-center gap-1.5 h-4">
                                        @foreach($eventsByPlayer->get($player->id, []) as $event)
                                            @if($event->event_type == 'goal' || $event->event_type == 'penalty')
                                                <span class="text-[10px]" title="Goal {{ $event->minute }}'">⚽</span>
                                            @elseif($event->event_type == 'yellow_card')
                                                <div class="w-1.5 h-2.5 bg-yellow-400 rounded-sm" title="Yellow Card {{ $event->minute }}'"></div>
                                            @elseif($event->event_type == 'red_card')
                                                <div class="w-1.5 h-2.5 bg-red-600 rounded-sm" title="Red Card {{ $event->minute }}'"></div>
                                            @elseif($event->event_type == 'sub_off')
                                                <div class="flex items-center justify-center w-3 h-3 md:w-3.5 md:h-3.5 bg-rose-500 rounded-full text-white text-[7px] md:text-[8px] font-black shadow-sm" title="Off {{ $event->minute }}'">⬇</div>
                                            @elseif($event->event_type == 'sub_on')
                                                <div class="flex items-center justify-center w-3 h-3 md:w-3.5 md:h-3.5 bg-emerald-500 rounded-full text-white text-[7px] md:text-[8px] font-black shadow-sm" title="On {{ $event->minute }}'">➜</div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-[10px] text-zinc-300 italic">No starting XI recorded.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Substitutes Section -->
            <div class="space-y-8 pt-8 border-t border-zinc-100">
                <!-- Visual Squad Bench -->
                <div class="bg-white rounded-[2.5rem] border border-zinc-200 p-10 shadow-sm overflow-hidden">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-10 flex items-center gap-3">
                        <span class="w-2.5 h-2.5 bg-indigo-600 rounded-full shadow-[0_0_10px_rgba(79,70,229,0.5)]"></span>
                        Squad Bench
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-4">
                        <!-- Home Subs Visual -->
                        <div class="space-y-4">
                            @foreach($homeSubs as $player)
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-zinc-50 border border-zinc-100 overflow-hidden flex-shrink-0">
                                            @if($player->image_url)
                                                <img src="{{ $player->image_url }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <span class="text-[10px] font-black text-zinc-300 uppercase">{{ substr($player->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-zinc-800 uppercase leading-none">{{ $player->name }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[8px] font-black text-zinc-400 italic">#{{ $player->shirt_number }} {{ $player->position }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @php $pEvents = collect($eventsByPlayer->get($player->id, [])); @endphp
                                    <div class="flex items-center gap-2">
                                        @if($pEvents->where('event_type', 'sub_on')->first())
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex items-center justify-center w-5 h-5 bg-emerald-500 rounded-full text-white text-[10px] font-black shadow-sm">⬆</div>
                                                <span class="text-[9px] font-black text-zinc-400">{{ $pEvents->where('event_type', 'sub_on')->first()->minute }}'</span>
                                            </div>
                                        @endif
                                        @if($pEvents->where('event_type', 'sub_off')->first())
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex items-center justify-center w-5 h-5 bg-rose-500 rounded-full text-white text-[10px] font-black shadow-sm">⬇</div>
                                                <span class="text-[9px] font-black text-zinc-400">{{ $pEvents->where('event_type', 'sub_off')->first()->minute }}'</span>
                                            </div>
                                        @endif
                                        @if($pEvents->whereIn('event_type', ['goal', 'penalty'])->count() > 0)
                                            <span class="text-[10px]">⚽</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Away Subs Visual -->
                        <div class="space-y-4">
                            @foreach($awaySubs as $player)
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-zinc-50 border border-zinc-100 overflow-hidden flex-shrink-0">
                                            @if($player->image_url)
                                                <img src="{{ $player->image_url }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <span class="text-[10px] font-black text-zinc-300 uppercase">{{ substr($player->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-zinc-800 uppercase leading-none">{{ $player->name }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[8px] font-black text-zinc-400 italic">#{{ $player->shirt_number }} {{ $player->position }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @php $pEvents = collect($eventsByPlayer->get($player->id, [])); @endphp
                                    <div class="flex items-center gap-2">
                                        @if($pEvents->where('event_type', 'sub_on')->first())
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex items-center justify-center w-5 h-5 bg-emerald-500 rounded-full text-white text-[10px] font-black shadow-sm">⬆</div>
                                                <span class="text-[9px] font-black text-zinc-400">{{ $pEvents->where('event_type', 'sub_on')->first()->minute }}'</span>
                                            </div>
                                        @endif
                                        @if($pEvents->where('event_type', 'sub_off')->first())
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex items-center justify-center w-5 h-5 bg-rose-500 rounded-full text-white text-[10px] font-black shadow-sm">⬇</div>
                                                <span class="text-[9px] font-black text-zinc-400">{{ $pEvents->where('event_type', 'sub_off')->first()->minute }}'</span>
                                            </div>
                                        @endif
                                        @if($pEvents->whereIn('event_type', ['goal', 'penalty'])->count() > 0)
                                            <span class="text-[10px]">⚽</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Substitutes Lists (Double Column) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Home Substitutes List -->
                    <div class="bg-zinc-50/50 p-8 rounded-[2rem] border border-zinc-100">
                        <h3 class="text-[9px] font-black text-zinc-400 uppercase tracking-widest mb-6">Home Substitutes</h3>
                        <div class="space-y-4">
                            @forelse($homeSubs as $player)
                                <div class="flex items-center justify-between opacity-75 hover:opacity-100 transition">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black text-zinc-300 w-4">#{{ $player->pivot->shirt_number ?? '-' }}</span>
                                        <span class="text-xs font-bold text-zinc-500">{{ $player->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        @foreach($eventsByPlayer->get($player->id, []) as $event)
                                            @if($event->event_type == 'sub_on')
                                                <span class="text-[10px] text-emerald-500 font-black" title="On {{ $event->minute }}'">⬆ {{ $event->minute }}'</span>
                                            @elseif($event->event_type == 'goal')
                                                <span class="text-[10px]">⚽</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                 <p class="text-[10px] text-zinc-300 italic">No substitutes recorded.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Away Substitutes List -->
                    <div class="bg-zinc-50/50 p-8 rounded-[2rem] border border-zinc-100">
                        <h3 class="text-[9px] font-black text-zinc-400 uppercase tracking-widest mb-6">Away Substitutes</h3>
                        <div class="space-y-4">
                            @forelse($awaySubs as $player)
                                <div class="flex items-center justify-between opacity-75 hover:opacity-100 transition">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black text-zinc-300 w-4">#{{ $player->pivot->shirt_number ?? '-' }}</span>
                                        <span class="text-xs font-bold text-zinc-500">{{ $player->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        @foreach($eventsByPlayer->get($player->id, []) as $event)
                                            @if($event->event_type == 'sub_on')
                                                <span class="text-[10px] text-emerald-500 font-black" title="On {{ $event->minute }}'">⬆ {{ $event->minute }}'</span>
                                            @elseif($event->event_type == 'goal')
                                                <span class="text-[10px]">⚽</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                 <p class="text-[10px] text-zinc-300 italic">No substitutes recorded.</p>
                            @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

    <!-- STATS TAB -->
    <div x-show="tab === 'stats'" x-cloak>
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-6 md:p-8 space-y-6 md:space-y-10">
            <h3 class="text-[10px] md:text-xs font-black text-primary uppercase tracking-widest border-b border-zinc-50 pb-4">Match Statistics</h3>

            @php
                $stats = [
                    ['Possession', $match->home_possession, $match->away_possession, '%'],
                    ['Shots', $match->home_shots, $match->away_shots, ''],
                    ['Offside', $match->home_offsides, $match->away_offsides, ''],
                    ['Corner', $match->home_corners, $match->away_corners, ''],
                    ['Free Kicks', $match->home_free_kicks, $match->away_free_kicks, ''],
                    ['Throw-ins', $match->home_throw_ins, $match->away_throw_ins, ''],
                    ['Fouls', $match->home_fouls, $match->away_fouls, ''],
                    ['Goal-Kicks', $match->home_goal_kicks, $match->away_goal_kicks, ''],
                    ['Saves', $match->home_saves, $match->away_saves, ''],
                    ['Yellow Cards', $match->matchEvents->where('event_type', 'yellow_card')->where('team_id', $match->home_team_id)->count(), $match->matchEvents->where('event_type', 'yellow_card')->where('team_id', $match->away_team_id)->count(), ''],
                    ['Red Cards', $match->matchEvents->where('event_type', 'red_card')->where('team_id', $match->home_team_id)->count(), $match->matchEvents->where('event_type', 'red_card')->where('team_id', $match->away_team_id)->count(), ''],
                ];
            @endphp

            <div class="grid grid-cols-1 gap-6 md:gap-8">
                @foreach($stats as $stat)
                    @php
                        $label = $stat[0];
                        $homeVal = $stat[1] ?? 0;
                        $awayVal = $stat[2] ?? 0;
                        $suffix = $stat[3];
                        $total = ($homeVal + $awayVal) ?: 100;
                        $homePercent = ($homeVal / $total) * 100;
                        $awayPercent = ($awayVal / $total) * 100;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] md:text-xs font-bold text-primary uppercase tracking-widest">
                            <span class="{{ $homeVal > $awayVal ? 'text-indigo-600' : '' }}">{{ $homeVal }}{{ $suffix }}</span>
                            <span class="text-zinc-400">{{ $label }}</span>
                            <span class="{{ $awayVal > $homeVal ? 'text-rose-600' : '' }}">{{ $awayVal }}{{ $suffix }}</span>
                        </div>
                        <div class="flex h-2.5 md:h-3 rounded-full overflow-hidden bg-zinc-100">
                            <div class="bg-indigo-600 transition-all duration-1000" style="width: {{ $homePercent }}%"></div>
                            <div class="bg-rose-600 transition-all duration-1000" style="width: {{ $awayPercent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- INFO TAB -->
    <div x-show="tab === 'info'" x-cloak>
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
             <div class="space-y-1.5 md:space-y-2">
                <label class="block text-[9px] md:text-[10px] font-black text-zinc-400 uppercase tracking-widest">Date & Time</label>
                <p class="text-xs md:text-sm font-bold text-primary">{{ $match->match_date->format('l jS F Y, H:i') }}</p>
            </div>
            <div class="space-y-1.5 md:space-y-2">
                <label class="block text-[9px] md:text-[10px] font-black text-zinc-400 uppercase tracking-widest">Venue</label>
                <p class="text-xs md:text-sm font-bold text-primary">{{ $match->venue }}</p>
            </div>
            <div class="space-y-1.5 md:space-y-2">
                <label class="block text-[9px] md:text-[10px] font-black text-zinc-400 uppercase tracking-widest">Referee</label>
                <p class="text-xs md:text-sm font-bold text-primary">{{ $match->referee ?? 'TBA' }}</p>
            </div>
            <div class="space-y-1.5 md:space-y-2">
                <label class="block text-[9px] md:text-[10px] font-black text-zinc-400 uppercase tracking-widest">Attendance</label>
                <p class="text-xs md:text-sm font-bold text-primary">{{ number_format($match->attendance) ?? '-' }}</p>
            </div>
            
            <div class="col-span-1 md:col-span-2 mt-2 md:mt-4 pt-4 md:pt-4 border-t border-zinc-50">
                 <label class="block text-[9px] md:text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Match Report</label>
                 <p class="text-[11px] md:text-xs leading-relaxed text-zinc-500 font-medium">{{ $match->report ?? 'No report available.' }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
