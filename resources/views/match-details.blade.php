@extends('layout')

@section('title', $match->homeTeam->name . ' vs ' . $match->awayTeam->name)

@section('content')
<div x-data="{ tab: 'recap' }" class="max-w-4xl mx-auto space-y-8 pb-24">
    <!-- Breadcrumbs -->
    <nav class="flex text-[10px] font-bold text-zinc-400 uppercase tracking-widest gap-2">
        <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
        <span>/</span>
        <a href="{{ route('results') }}" class="hover:text-primary transition">Results</a>
        <span>/</span>
        <span class="text-primary">Match Details</span>
    </nav>

    <!-- Premium Dynamic Match Header Card -->
    <div class="rounded-[2.5rem] shadow-2xl overflow-hidden relative border-4 border-white/20">
        <!-- Dynamic Gradient Background (Removed Black/Gray) -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#3d195b] via-[#6d28d9] to-[#00ff85] animate-gradient-xy"></div>
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        
        <!-- Content Container -->
        <div class="relative z-10">
            <div class="grid grid-cols-3 items-center py-10 px-8">
                <!-- Home Team -->
                <div class="flex flex-col items-center gap-4 group">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-white/20 rounded-full blur-xl group-hover:bg-white/30 transition duration-500"></div>
                        @if($match->homeTeam->logo_url)
                        <img src="{{ $match->homeTeam->logo_url }}" class="w-24 h-24 object-contain relative z-10 drop-shadow-[0_10px_10px_rgba(0,0,0,0.3)] group-hover:scale-110 transition duration-500">
                        @else
                        <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center font-black text-4xl text-white relative z-10 border-2 border-white/30 group-hover:border-white transition duration-500">
                            {{ substr($match->homeTeam->name, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <h2 class="text-xl font-black text-white uppercase tracking-tighter text-center leading-tight drop-shadow-lg max-w-[140px]">{{ $match->homeTeam->name }}</h2>
                </div>

                <!-- Score / VS Center -->
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-white/10 backdrop-blur-2xl rounded-3xl p-6 md:p-8 border border-white/30 shadow-2xl">
                        @if($match->status === 'finished')
                        <div class="flex flex-col items-center">
                            <div class="text-5xl md:text-6xl font-black text-white tracking-widest flex items-center gap-4 md:gap-6 drop-shadow-[0_5px_15px_rgba(0,0,0,0.5)]">
                                <span class="text-secondary">{{ $match->home_score }}</span>
                                <span class="text-white/40 text-4xl">:</span>
                                <span class="text-accent">{{ $match->away_score }}</span>
                            </div>
                            <div class="mt-4 px-4 py-1.5 bg-secondary text-primary rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg shadow-secondary/20">Full Time</div>
                        </div>
                        @else
                        <div class="flex flex-col items-center">
                            <div class="text-4xl md:text-5xl font-black text-white tracking-tighter italic drop-shadow-lg">VS</div>
                            <div class="mt-4 px-4 py-1.5 bg-white text-primary rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg">{{ \Carbon\Carbon::parse($match->match_date)->format('H:i') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Away Team -->
                <div class="flex flex-col items-center gap-4 group">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-white/20 rounded-full blur-xl group-hover:bg-white/30 transition duration-500"></div>
                        @if($match->awayTeam->logo_url)
                        <img src="{{ $match->awayTeam->logo_url }}" class="w-24 h-24 object-contain relative z-10 drop-shadow-[0_10px_10px_rgba(0,0,0,0.3)] group-hover:scale-110 transition duration-500">
                        @else
                        <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center font-black text-4xl text-white relative z-10 border-2 border-white/30 group-hover:border-white transition duration-500">
                            {{ substr($match->awayTeam->name, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <h2 class="text-xl font-black text-white uppercase tracking-tighter text-center leading-tight drop-shadow-lg max-w-[140px]">{{ $match->awayTeam->name }}</h2>
                </div>
            </div>

            <!-- Meta Bar Overlay (Dynamic Indigo) -->
            <div class="bg-indigo-950/40 backdrop-blur-xl border-t border-white/10 px-8 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></div>
                    <span class="text-[10px] font-black text-white uppercase tracking-[0.25em]">{{ $match->venue }}</span>
                </div>
                <div class="flex items-center gap-6">
                    <span class="text-[10px] font-black text-white/70 uppercase tracking-[0.25em]">{{ \Carbon\Carbon::parse($match->match_date)->format('l j F Y') }}</span>
                    @if($match->stage === 'novelty')
                    <span class="text-[8px] font-black text-primary bg-secondary px-3 py-1 rounded-sm uppercase tracking-widest">Exhibition</span>
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
    <div x-show="tab === 'recap'" class="space-y-8">

    <!-- Timeline & Events -->
    @if($match->status === 'finished')
    <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-8">
        @forelse($match->matchEvents as $event)
        <div class="grid grid-cols-[1fr,auto,1fr] gap-4 items-center mb-8 last:mb-0 relative group">
            <!-- Home Team Events (Left) -->
            <div class="flex justify-end items-center gap-3 text-right">
                @if($event->team_id == $match->home_team_id)
                    <div class="flex flex-col items-end">
                        <span class="font-bold text-primary text-sm leading-tight">{{ $event->player_name }}</span>
                        @if($event->event_type === 'goal')
                            @php $assist = $match->matchEvents->where('event_type', 'assist')->where('minute', $event->minute)->where('team_id', $event->team_id)->first(); @endphp
                        @endif
                        
                        @if($event->assistant)
                        <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-wide">{{ $event->assistant->name }} (Assist)</span>
                        @endif
                    </div>
                    
                    @if($event->event_type === 'goal')
                    <div class="w-6 h-6 flex items-center justify-center text-lg">⚽</div>
                    @elseif($event->event_type === 'yellow_card')
                    <div class="w-4 h-5 bg-yellow-400 rounded-sm shadow-sm rotate-6"></div>
                    @elseif($event->event_type === 'red_card')
                    <div class="w-4 h-5 bg-red-600 rounded-sm shadow-sm rotate-6"></div>
                    @elseif($event->event_type === 'substitution')
                    <div class="w-6 h-6 flex items-center justify-center text-zinc-400">🔄</div>
                    @endif
                @endif
            </div>

            <!-- Minute (Center) -->
            <div class="w-10 flex justify-center">
                <span class="text-xs font-black text-zinc-300 group-hover:text-primary transition">{{ $event->minute }}'</span>
            </div>

            <!-- Away Team Events (Right) -->
            <div class="flex justify-start items-center gap-3 text-left">
                @if($event->team_id == $match->away_team_id)
                    @if($event->event_type === 'goal')
                    <div class="w-6 h-6 flex items-center justify-center text-lg">⚽</div>
                    @elseif($event->event_type === 'yellow_card')
                    <div class="w-4 h-5 bg-yellow-400 rounded-sm shadow-sm -rotate-6"></div>
                    @elseif($event->event_type === 'red_card')
                    <div class="w-4 h-5 bg-red-600 rounded-sm shadow-sm -rotate-6"></div>
                    @elseif($event->event_type === 'substitution')
                    <div class="w-6 h-6 flex items-center justify-center text-zinc-400">🔄</div>
                    @endif

                    <div class="flex flex-col items-start">
                        <span class="font-bold text-primary text-sm leading-tight">{{ $event->player_name }}</span>
                        @if($event->assistant)
                        <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-wide">{{ $event->assistant->name }} (Assist)</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <span class="text-xs font-black text-zinc-300 uppercase italic tracking-widest">No detailed events recorded for this match.</span>
        </div>
        @endforelse

        @if($match->motm_player_id)
        <div class="mt-12 pt-12 border-t border-zinc-100 flex flex-col items-center text-center">
            <span class="text-[10px] font-black text-secondary uppercase tracking-widest bg-primary px-3 py-1 rounded-full mb-4 shadow-lg border-2 border-primary ring-2 ring-secondary/50">Man of the Match</span>
            @if($match->motmPlayer->image_url)
            <img src="{{ $match->motmPlayer->image_url }}" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg mb-3">
            @endif
            <h3 class="text-xl font-black text-primary uppercase italic tracking-tighter leading-none">{{ $match->motmPlayer->name }}</h3>
            <span class="text-xs font-bold text-zinc-400 mt-1 uppercase tracking-wide">{{ $match->motmPlayer->team->name }}</span>
        </div>
        @endif
    </div>
    @endif
    </div>

    <!-- LINEUPS TAB -->
    <div x-show="tab === 'lineups'" style="display: none;">
        @php
            $eventsByPlayer = $match->matchEvents->groupBy('player_id');
            $homeStartingXI = $match->lineups->where('pivot.team_id', $match->home_team_id)->where('pivot.is_substitute', false);
            $awayStartingXI = $match->lineups->where('pivot.team_id', $match->away_team_id)->where('pivot.is_substitute', false);
            $homeSubs = $match->lineups->where('pivot.team_id', $match->home_team_id)->where('pivot.is_substitute', true);
            $awaySubs = $match->lineups->where('pivot.team_id', $match->away_team_id)->where('pivot.is_substitute', true);
        @endphp

        <div class="space-y-12">
            <!-- Starting XI Section -->
            <div class="space-y-8">
                <div class="relative">
                    <!-- Row-Based Green Horizontal Pitch (Restored per Sketch) -->
                <div class="relative bg-white border border-zinc-200 rounded-[2.5rem] overflow-hidden shadow-2xl mb-12 aspect-video max-w-5xl mx-auto p-4 flex flex-col">
                    <!-- The Inner Green Pitch -->
                    <div class="relative flex-grow bg-emerald-600 rounded-[2rem] border-4 border-emerald-500 overflow-hidden shadow-inner">
                        <!-- Field Markings (White) -->
                        <div class="absolute inset-0 pointer-events-none opacity-30">
                            <!-- Outer Frame -->
                            <div class="absolute inset-4 border-2 border-white rounded-2xl"></div>
                            <!-- Center Line -->
                            <div class="absolute top-4 bottom-4 left-1/2 w-0.5 bg-white"></div>
                            <!-- Center Circle -->
                            <div class="absolute top-1/2 left-1/2 w-48 h-48 border-2 border-white rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
                            <!-- Goal Areas -->
                            <div class="absolute top-1/4 bottom-1/4 left-4 w-28 border-2 border-l-0 border-white rounded-r-xl"></div>
                            <div class="absolute top-1/4 bottom-1/4 right-4 w-28 border-2 border-r-0 border-white rounded-l-xl"></div>
                        </div>

                        <!-- Team Headers (Corner Placement like Sketch) -->
                        <div class="absolute top-6 left-6 right-6 flex justify-between items-start pointer-events-none z-20">
                            <div class="flex items-center gap-3">
                                <img src="{{ $match->homeTeam->logo_url }}" class="w-12 h-12 object-contain drop-shadow-lg">
                                <div class="bg-white/90 backdrop-blur px-3 py-1.5 rounded-xl border border-white/20 shadow-md">
                                    <h3 class="text-[11px] font-black text-indigo-900 uppercase leading-tight">{{ $match->homeTeam->name }}</h3>
                                    <p class="text-[9px] font-bold text-indigo-400 mt-0.5">FORMATION 4-3-3</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-right">
                                <div class="bg-white/90 backdrop-blur px-3 py-1.5 rounded-xl border border-white/20 shadow-md">
                                    <h3 class="text-[11px] font-black text-rose-900 uppercase leading-tight">{{ $match->awayTeam->name }}</h3>
                                    <p class="text-[9px] font-bold text-rose-400 mt-0.5">FORMATION 4-2-3-1</p>
                                </div>
                                <img src="{{ $match->awayTeam->logo_url }}" class="w-12 h-12 object-contain drop-shadow-lg">
                            </div>
                        </div>

                        <!-- Players (Row-based) -->
                        @foreach($match->lineups as $player)
                            @if(!$player->pivot->is_substitute && $player->pivot->position_x && $player->pivot->position_y)
                            <div class="absolute transform -translate-x-1/2 -translate-y-1/2 z-10"
                                    style="left: {{ $player->pivot->position_x }}%; top: {{ $player->pivot->position_y }}%;">
                                
                                <div class="flex flex-col items-center group">
                                    <!-- Circular Headshot -->
                                    <div class="relative w-14 h-14 mb-1">
                                        <div class="w-full h-full rounded-full bg-white border-2 {{ $player->pivot->team_id == $match->home_team_id ? 'border-primary' : 'border-rose-500' }} overflow-hidden shadow-lg group-hover:scale-110 transition-transform shadow-black/30">
                                            @if($player->image_url)
                                                <img src="{{ $player->image_url }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-zinc-50">
                                                    <span class="text-[10px] font-black text-zinc-300">{{ substr($player->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Event Badges -->
                                        @php $playerEvents = collect($eventsByPlayer->get($player->id, [])); @endphp
                                        @if($playerEvents->whereIn('event_type', ['goal', 'penalty'])->count() > 0)
                                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center shadow-md border border-zinc-100 z-20">
                                                <span class="text-[9px]">⚽</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Label -->
                                    <div class="text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="text-[8px] font-black text-white/60 leading-none">#{{ $player->pivot->shirt_number ?? $player->shirt_number }}</span>
                                            <span class="text-[10px] font-black text-white uppercase tracking-tighter drop-shadow-md">{{ Str::afterLast($player->name, ' ') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach

                        <!-- Managers Footer (Base of Inner Pitch) -->
                        <div class="absolute bottom-6 left-10 right-10 flex justify-between items-end pointer-events-none z-20">
                            <div class="bg-white/10 backdrop-blur px-3 py-1 rounded-lg border border-white/10">
                                <span class="text-[8px] font-bold text-white/50 uppercase tracking-[0.2em] block mb-0.5">Manager</span>
                                <span class="text-[10px] font-black text-white uppercase">{{ $match->homeTeam->manager ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-white/10 backdrop-blur px-3 py-1 rounded-lg border border-white/10 text-right">
                                <span class="text-[8px] font-bold text-white/50 uppercase tracking-[0.2em] block mb-0.5">Manager</span>
                                <span class="text-[10px] font-black text-white uppercase">{{ $match->awayTeam->manager ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Starting XI Lists (Double Column) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Home Starting XI -->
                    <div class="bg-white p-8 rounded-[2rem] border border-zinc-100 shadow-sm">
                        <h3 class="text-[10px] font-black text-primary uppercase tracking-widest flex items-center justify-between mb-6">
                            <span>{{ $match->homeTeam->name }} Starting XI</span>
                            <span class="text-zinc-300">XI</span>
                        </h3>
                        <div class="space-y-4">
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
                                                <span class="text-[10px] text-red-500 font-black" title="Off {{ $event->minute }}'">⬇</span>
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
                    <div class="bg-white p-8 rounded-[2rem] border border-zinc-100 shadow-sm">
                        <h3 class="text-[10px] font-black text-rose-600 uppercase tracking-widest flex items-center justify-between mb-6">
                            <span>{{ $match->awayTeam->name }} Starting XI</span>
                            <span class="text-zinc-300">XI</span>
                        </h3>
                        <div class="space-y-4">
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
                                                <span class="text-[10px] text-red-500 font-black" title="Off {{ $event->minute }}'">⬇</span>
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
                                                <span class="text-green-500 font-bold scale-x-[-1]">➜</span>
                                                <span class="text-[9px] font-black text-zinc-400">{{ $pEvents->where('event_type', 'sub_on')->first()->minute }}'</span>
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
                                                <span class="text-green-500 font-bold scale-x-[-1]">➜</span>
                                                <span class="text-[9px] font-black text-zinc-400">{{ $pEvents->where('event_type', 'sub_on')->first()->minute }}'</span>
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

    <!-- STATS TAB -->
    <div x-show="tab === 'stats'" style="display: none;">
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-8 space-y-8">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest border-b border-zinc-50 pb-4">Match Statistics</h3>
            
            <!-- Possession -->
            @if($match->home_possession > 0 || $match->away_possession > 0)
            <div class="space-y-2">
                <div class="flex justify-between text-xs font-bold text-primary uppercase tracking-widest">
                    <span>{{ $match->home_possession }}%</span>
                    <span class="text-zinc-400">Possession</span>
                    <span>{{ $match->away_possession }}%</span>
                </div>
                <div class="flex h-3 rounded-full overflow-hidden bg-zinc-100">
                    <div class="bg-indigo-600" style="width: {{ $match->home_possession }}%"></div>
                    <div class="bg-rose-600" style="width: {{ $match->away_possession }}%"></div>
                </div>
            </div>
            @endif

            <!-- Shots -->
            @if($match->home_shots > 0 || $match->away_shots > 0)
            <div class="space-y-2">
                <div class="flex justify-between text-xs font-bold text-primary uppercase tracking-widest">
                    <span>{{ $match->home_shots }}</span>
                    <span class="text-zinc-400">Shots</span>
                    <span>{{ $match->away_shots }}</span>
                </div>
                 <div class="flex h-3 rounded-full overflow-hidden bg-zinc-100 relative">
                     <div class="absolute left-1/2 top-0 bottom-0 w-px bg-white z-10"></div>
                     <!-- Simple bar chart from center? Or just full width comparisons -->
                     <div class="w-1/2 flex justify-end bg-zinc-50">
                        <div class="bg-indigo-600 h-full" style="width: {{ $match->home_shots * 5 }}%"></div>
                     </div>
                     <div class="w-1/2 flex justify-start bg-zinc-50">
                        <div class="bg-rose-600 h-full" style="width: {{ $match->away_shots * 5 }}%"></div>
                     </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- INFO TAB -->
    <div x-show="tab === 'info'" style="display: none;">
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-8 grid grid-cols-2 gap-8">
             <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest">Date & Time</label>
                <p class="text-sm font-bold text-primary">{{ $match->match_date->format('l jS F Y, H:i') }}</p>
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest">Venue</label>
                <p class="text-sm font-bold text-primary">{{ $match->venue }}</p>
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest">Referee</label>
                <p class="text-sm font-bold text-primary">{{ $match->referee ?? 'TBA' }}</p>
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest">Attendance</label>
                <p class="text-sm font-bold text-primary">{{ number_format($match->attendance) ?? '-' }}</p>
            </div>
            
            <div class="col-span-2 mt-4 pt-4 border-t border-zinc-50">
                 <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Match Report</label>
                 <p class="text-xs leading-relaxed text-zinc-500 font-medium">{{ $match->report ?? 'No report available.' }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
