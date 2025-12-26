@extends('layout')

@section('content')
<div class="space-y-12">
    <!-- Hero Section -->
    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
        <div class="absolute inset-0 bg-zinc-900">
            <div class="absolute inset-0 opacity-80" style="background-color: {{ $team->primary_color ?? '#000000' }}"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20"></div>
        </div>

        <div class="relative p-6 md:p-16 flex flex-col md:flex-row items-center gap-8 md:gap-10">
            <!-- Large Logo -->
            <div class="w-32 h-32 md:w-56 md:h-56 bg-white rounded-full flex items-center justify-center shadow-2xl p-4 transform hover:scale-105 transition duration-500">
                @if($team->logo_url)
                <img src="{{ $team->logo_url }}" class="w-full h-full object-contain">
                @else
                <span class="text-4xl font-black text-zinc-300">{{ substr($team->name, 0, 1) }}</span>
                @endif
            </div>

            <!-- Team Info -->
            <div class="text-center md:text-left space-y-3 md:space-y-4 flex-1">
                <div class="inline-block px-3 py-1 md:px-4 md:py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] md:text-xs font-black uppercase tracking-widest">
                    {{ $team->group->name }}
                </div>
                <h1 class="text-4xl md:text-7xl font-black text-white uppercase tracking-tighter leading-tight italic drop-shadow-lg">
                    {{ $team->name }}
                </h1>
                <div class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-3 md:gap-6 text-white/90">
                    <div class="flex items-center gap-2">
                        <img src="/images/stadium-icon.png" class="w-4 h-4 md:w-5 md:h-5 brightness-0 invert">
                        <span class="font-bold text-base md:text-lg">{{ $team->stadium_name ?? 'Stadium TBD' }}</span>
                    </div>
                    @if($team->manager)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="font-bold text-base md:text-lg">Mgr. {{ $team->manager }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-2 gap-3 w-full md:w-64">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 md:p-4 text-center border border-white/10">
                    <span class="block text-2xl md:text-3xl font-black text-white">{{ $team->points }}</span>
                    <span class="text-[9px] md:text-[10px] text-white/60 uppercase tracking-widest font-bold">Points</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 md:p-4 text-center border border-white/10">
                    <span class="block text-2xl md:text-3xl font-black text-white">#{{ $rank }}</span>
                    <span class="text-[9px] md:text-[10px] text-white/60 uppercase tracking-widest font-bold">Rank</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 md:p-4 text-center border border-white/10 col-span-2 flex justify-between px-4 md:px-6">
                    <div class="text-center">
                        <span class="block text-lg md:text-xl font-black text-green-400">{{ $team->wins }}</span>
                        <span class="text-[8px] text-white/60 uppercase font-bold">W</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-lg md:text-xl font-black text-zinc-400">{{ $team->draws }}</span>
                        <span class="text-[8px] text-white/60 uppercase font-bold">D</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-lg md:text-xl font-black text-red-400">{{ $team->losses }}</span>
                        <span class="text-[8px] text-white/60 uppercase font-bold">L</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Statistics Section -->
    <div class="bg-white rounded-3xl p-6 md:p-10 shadow-sm border border-zinc-100">
        <h2 class="text-2xl md:text-3xl font-black text-primary uppercase tracking-tighter italic mb-6 md:mb-8 flex items-center gap-3">
            <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Team Statistics
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
            <!-- Goals Scored -->
            <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-4 md:p-5 border border-green-100 hover:shadow-lg transition relative group">
                @if(isset($rankings['goals_for']) && $rankings['goals_for'])
                <div class="absolute top-3 right-3 px-2 py-0.5 md:px-3 md:py-1 rounded-lg bg-green-500 text-white text-[8px] md:text-[10px] font-black italic shadow-lg transform group-hover:-translate-y-1 transition duration-300">
                    RANK #{{ $rankings['goals_for'] }}
                </div>
                @endif
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-green-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <span class="block text-3xl md:text-4xl font-black text-green-600">{{ $team->goals_for }}</span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Goals Scored</span>
            </div>

            <!-- Goals Conceded -->
            <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl p-4 md:p-5 border border-red-100 hover:shadow-lg transition relative group">
                @if(isset($rankings['goals_against']) && $rankings['goals_against'])
                <div class="absolute top-3 right-3 px-2 py-0.5 md:px-3 md:py-1 rounded-lg bg-red-600 text-white text-[8px] md:text-[10px] font-black italic shadow-lg transform group-hover:-translate-y-1 transition duration-300">
                    RANK #{{ $rankings['goals_against'] }}
                </div>
                @endif
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-red-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    </div>
                </div>
                <span class="block text-3xl md:text-4xl font-black text-red-600">{{ $team->goals_against }}</span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Goals Conceded</span>
            </div>

            <!-- Goal Difference -->
            <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-4 md:p-5 border border-blue-100 hover:shadow-lg transition">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-blue-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    </div>
                </div>
                <span class="block text-3xl md:text-4xl font-black {{ ($team->goals_for - $team->goals_against) >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                    {{ $team->goals_for - $team->goals_against > 0 ? '+' : '' }}{{ $team->goals_for - $team->goals_against }}
                </span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Goal Difference</span>
            </div>

            <!-- Clean Sheets -->
            <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-4 md:p-5 border border-purple-100 hover:shadow-lg transition">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-purple-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>
                <span class="block text-3xl md:text-4xl font-black text-purple-600">{{ $cleanSheets }}</span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Clean Sheets</span>
            </div>

            <!-- Win Rate -->
            <div class="bg-gradient-to-br from-amber-50 to-white rounded-2xl p-4 md:p-5 border border-amber-100 hover:shadow-lg transition">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-amber-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                </div>
                <span class="block text-3xl md:text-4xl font-black text-amber-600">{{ $winRate }}%</span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Win Rate</span>
            </div>

            <!-- Goals Per Game -->
            <div class="bg-gradient-to-br from-emerald-50 to-white rounded-2xl p-4 md:p-5 border border-emerald-100 hover:shadow-lg transition">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-emerald-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <span class="block text-3xl md:text-4xl font-black text-emerald-600">{{ $goalsPerGame }}</span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Goals Per Game</span>
            </div>

            <!-- Goals Conceded Per Game -->
            <div class="bg-gradient-to-br from-rose-50 to-white rounded-2xl p-4 md:p-5 border border-rose-100 hover:shadow-lg transition">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-rose-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <span class="block text-3xl md:text-4xl font-black text-rose-600">{{ $goalsConcededPerGame }}</span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Conceded Per Game</span>
            </div>

            <!-- Missed Chances -->
            <div class="bg-gradient-to-br from-zinc-50 to-white rounded-2xl p-4 md:p-5 border border-zinc-100 hover:shadow-lg transition">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-zinc-800 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <span class="block text-3xl md:text-4xl font-black text-zinc-800">{{ $totalMissedChances }}</span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Missed Chances</span>
            </div>

            <!-- Current Form -->
            <div class="bg-gradient-to-br from-indigo-50 to-white rounded-2xl p-4 md:p-5 border border-indigo-100 hover:shadow-lg transition col-span-2 md:col-span-1">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-indigo-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <div class="flex gap-1 mb-1">
                    @foreach(str_split($formString) as $result)
                        <span class="w-6 h-6 md:w-7 md:h-7 rounded-md flex items-center justify-center text-[10px] md:text-xs font-black {{ $result == 'W' ? 'bg-green-500 text-white' : ($result == 'D' ? 'bg-zinc-400 text-white' : 'bg-red-500 text-white') }}">
                            {{ $result }}
                        </span>
                    @endforeach
                </div>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Current Form</span>
            </div>

            <!-- Biggest Win -->
            @if($biggestWin)
            <div class="bg-gradient-to-br from-teal-50 to-white rounded-2xl p-4 md:p-5 border border-teal-100 hover:shadow-lg transition col-span-2 md:col-span-1">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-teal-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                </div>
                <span class="block text-2xl md:text-3xl font-black text-teal-600">{{ $biggestWin->home_score }} - {{ $biggestWin->away_score }}</span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Biggest Win</span>
                <span class="text-[8px] text-zinc-400 block mt-1">vs {{ $biggestWin->home_team_id == $team->id ? $biggestWin->awayTeam->name : $biggestWin->homeTeam->name }}</span>
            </div>
            @endif

            <!-- Biggest Loss -->
            @if($biggestLoss)
            <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl p-4 md:p-5 border border-orange-100 hover:shadow-lg transition col-span-2 md:col-span-1">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-orange-500 flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                </div>
                <span class="block text-2xl md:text-3xl font-black text-orange-600">{{ $biggestLoss->home_score }} - {{ $biggestLoss->away_score }}</span>
                <span class="text-[9px] md:text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Biggest Loss</span>
                <span class="text-[8px] text-zinc-400 block mt-1">vs {{ $biggestLoss->home_team_id == $team->id ? $biggestLoss->awayTeam->name : $biggestLoss->homeTeam->name }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main Content: Squad -->
        <div class="lg:col-span-2 space-y-8 md:space-y-12">
            <h3 class="text-2xl md:text-3xl font-black text-primary uppercase tracking-tighter flex items-center gap-3 italic px-4 md:px-0">
                The Squad
                <span class="bg-zinc-100 text-zinc-500 text-[10px] md:text-xs px-2 md:px-3 py-1 rounded-full not-italic">{{ $team->players->count() }} Players</span>
            </h3>

            @if($squad->count() > 0)
                @php
                    $positionOrder = ['GK', 'DEF', 'MID', 'FWD'];
                    $positionNames = [
                        'GK' => 'Goalkeepers',
                        'DEF' => 'Defenders',
                        'MID' => 'Midfielders',
                        'FWD' => 'Forwards'
                    ];
                @endphp
                @foreach($positionOrder as $pos)
                    @if(isset($squad[$pos]))
                    <div class="space-y-6 px-4 md:px-0">
                        <div class="flex items-center gap-4">
                            <h4 class="text-[10px] md:text-sm font-black text-zinc-400 uppercase tracking-widest">{{ $positionNames[$pos] ?? $pos }}</h4>
                            <div class="h-px bg-zinc-100 flex-1"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                            @foreach($squad[$pos] as $player)
                            <a href="{{ route('player.details', $player->id) }}" class="bg-white rounded-2xl border border-zinc-100 flex flex-col hover:shadow-xl transition-all duration-500 group overflow-hidden relative">
                                <!-- Background Team Color Accent -->
                                <div class="absolute top-0 right-0 w-24 h-24 md:w-32 md:h-32 -mr-12 -mt-12 md:-mr-16 md:-mt-16 rounded-full opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none" style="background-color: {{ $team->primary_color }}"></div>
                                
                                <div class="p-4 md:p-5 flex items-start gap-3 md:gap-4 flex-1">
                                    <!-- Player Image -->
                                    <div class="relative w-16 h-20 md:w-20 md:h-24 flex-none">
                                        @if($player->full_image_url)
                                            <img src="{{ $player->full_image_url }}" class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-full object-contain z-10 drop-shadow-lg group-hover:scale-110 transition-transform duration-500">
                                            <div class="absolute inset-0 bg-zinc-50 rounded-xl"></div>
                                        @elseif($player->image_url)
                                            <img src="{{ $player->image_url }}" class="w-full h-full object-cover rounded-xl border border-zinc-50 grayscale group-hover:grayscale-0 transition-all duration-500">
                                        @else
                                            <div class="w-full h-full bg-zinc-50 rounded-xl flex items-center justify-center font-black text-zinc-200 text-2xl md:text-3xl">
                                                {{ substr($player->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="absolute -bottom-1.5 -left-1.5 bg-white text-primary text-[8px] md:text-[10px] font-black px-1.5 md:px-2 py-0.5 md:py-1 rounded-md md:rounded-lg border border-zinc-100 shadow-sm z-20">#{{ $player->shirt_number ?? '--' }}</span>
                                    </div>

                                    <div class="flex-1 pt-1">
                                        <h4 class="font-black text-primary uppercase text-xs md:text-sm leading-tight group-hover:text-secondary transition-colors mb-1.5 md:mb-2">{{ $player->name }}</h4>
                                        <div class="flex flex-wrap gap-1">
                                            <span class="text-[7px] md:text-[8px] font-black bg-zinc-50 text-zinc-400 px-1.5 md:px-2 py-0.5 md:py-1 rounded-md uppercase tracking-widest">{{ $player->position }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stats Bar -->
                                <div class="bg-zinc-50 px-4 md:px-5 py-2 md:py-3 flex justify-between items-center border-t border-zinc-100">
                                    <div class="flex gap-3 md:gap-4">
                                        <div class="text-center">
                                            <span class="block text-[9px] md:text-[10px] font-black text-primary leading-none">{{ $player->match_lineups_count }}</span>
                                            <span class="text-[6px] md:text-[7px] font-bold text-zinc-400 uppercase tracking-tighter">Apps</span>
                                        </div>
                                        <div class="text-center">
                                            <span class="block text-[9px] md:text-[10px] font-black text-primary leading-none">{{ $player->goals_count }}</span>
                                            <span class="text-[6px] md:text-[7px] font-bold text-zinc-400 uppercase tracking-tighter">Goals</span>
                                        </div>
                                        <div class="text-center">
                                            <span class="block text-[9px] md:text-[10px] font-black text-primary leading-none">{{ $player->assists_count }}</span>
                                            <span class="text-[6px] md:text-[7px] font-bold text-zinc-400 uppercase tracking-tighter">Asst</span>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 md:w-6 md:h-6 rounded-md md:rounded-lg bg-white border border-zinc-200 flex items-center justify-center text-zinc-300 group-hover:bg-primary group-hover:text-white transition-all transform group-hover:rotate-45">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
            <div class="bg-zinc-50 rounded-xl p-8 md:p-12 text-center border border-zinc-100 border-dashed mx-4 md:mx-0">
                <span class="text-zinc-400 font-bold text-sm">No players registered yet.</span>
            </div>
            @endif
        </div>

        <!-- Sidebar: Matches -->
        <div class="space-y-8 md:space-y-12 px-4 md:px-0 pb-12">
            <!-- Next Match -->
            @if($nextMatch)
            <div class="space-y-4">
                <h3 class="text-lg md:text-xl font-black text-primary uppercase tracking-tighter italic">Next Match</h3>
                <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-zinc-100 border-l-4 border-l-secondary relative overflow-hidden">
                    <div class="flex flex-col items-center text-center space-y-4 relative z-10">
                        <span class="text-[10px] md:text-xs font-bold text-zinc-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($nextMatch->match_date)->format('D, M j • H:i') }}</span>
                        
                        <div class="flex items-center justify-between w-full gap-2">
                            <div class="flex flex-col items-center w-1/3">
                                <img src="{{ $nextMatch->homeTeam->logo_url ?? '' }}" class="w-10 h-10 md:w-12 md:h-12 object-contain mb-2">
                                <span class="font-bold text-[10px] md:text-xs leading-tight">{{ $nextMatch->homeTeam->name }}</span>
                            </div>
                            <span class="font-black text-xl md:text-2xl text-zinc-300 italic">VS</span>
                            <div class="flex flex-col items-center w-1/3">
                                <img src="{{ $nextMatch->awayTeam->logo_url ?? '' }}" class="w-10 h-10 md:w-12 md:h-12 object-contain mb-2">
                                <span class="font-bold text-[10px] md:text-xs leading-tight">{{ $nextMatch->awayTeam->name }}</span>
                            </div>
                        </div>

                        <a href="{{ route('match.details', $nextMatch->id) }}" class="w-full py-2.5 md:py-3 bg-zinc-900 text-white font-bold rounded-xl text-[10px] md:text-xs uppercase tracking-widest hover:bg-primary transition">Match Center</a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Results -->
            <div class="space-y-4">
                <h3 class="text-lg md:text-xl font-black text-primary uppercase tracking-tighter italic flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Recent Form
                </h3>
                <div class="space-y-3">
                    @foreach($recentMatches as $match)
                    @php
                        $isHome = $match->home_team_id == $team->id;
                        $teamScore = $isHome ? $match->home_score : $match->away_score;
                        $opponentScore = $isHome ? $match->away_score : $match->home_score;
                        $opponent = $isHome ? $match->awayTeam : $match->homeTeam;
                        $resultColor = $teamScore > $opponentScore ? 'bg-green-500' : ($teamScore == $opponentScore ? 'bg-zinc-400' : 'bg-red-500');
                        $resultChar = $teamScore > $opponentScore ? 'W' : ($teamScore == $opponentScore ? 'D' : 'L');
                        $borderColor = $teamScore > $opponentScore ? 'border-green-100' : ($teamScore == $opponentScore ? 'border-zinc-100' : 'border-red-100');
                    @endphp
                    <a href="{{ route('match.details', $match->id) }}" class="group bg-white rounded-2xl p-4 border {{ $borderColor }} hover:border-secondary hover:shadow-lg transition-all duration-300 block">
                        <!-- Result Badge & Date -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg {{ $resultColor }} flex items-center justify-center text-white text-xs font-black shadow-sm">
                                    {{ $resultChar }}
                                </div>
                                <span class="text-[9px] md:text-[10px] font-bold text-zinc-400 uppercase tracking-wider">
                                    {{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="text-xs font-black text-primary bg-zinc-50 px-3 py-1.5 rounded-lg group-hover:bg-secondary group-hover:text-white transition">
                                {{ $match->home_score }} - {{ $match->away_score }}
                            </div>
                        </div>

                        <!-- Teams -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 flex-1">
                                @if($opponent->logo_url)
                                <img src="{{ $opponent->logo_url }}" class="w-8 h-8 object-contain" alt="{{ $opponent->name }}">
                                @else
                                <div class="w-8 h-8 bg-zinc-100 rounded-lg flex items-center justify-center text-xs font-black text-zinc-300">
                                    {{ substr($opponent->name, 0, 1) }}
                                </div>
                                @endif
                                <div class="flex-1">
                                    <div class="text-xs font-bold text-zinc-500 mb-0.5">vs</div>
                                    <div class="text-sm font-black text-primary leading-tight">{{ $opponent->name }}</div>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-zinc-300 group-hover:text-secondary group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>

                        <!-- Match Details -->
                        <div class="mt-3 pt-3 border-t border-zinc-50 flex items-center justify-between text-[9px] text-zinc-400 font-bold uppercase tracking-wider">
                            <span>{{ $match->venue ?? 'Venue TBD' }}</span>
                            <span>{{ $match->stage }}</span>
                        </div>
                    </a>
                    @endforeach
                    @if($recentMatches->isEmpty())
                    <div class="bg-zinc-50 rounded-2xl p-8 text-center border border-zinc-100 border-dashed">
                        <svg class="w-12 h-12 mx-auto mb-3 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span class="text-zinc-400 font-bold text-xs uppercase tracking-widest">No recent matches played</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
