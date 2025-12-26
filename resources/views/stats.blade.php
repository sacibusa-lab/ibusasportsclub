@extends('layout')

@section('title', 'Stats Centre')

@section('content')
<!-- Premium Tournament Header -->
<div class="relative overflow-hidden bg-[#3d195b] mb-12 -mx-6 md:-mx-12 min-h-[300px] flex items-center">
    <!-- Animated background elements -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,#00ff85_0%,transparent_60%)] opacity-20"></div>
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
    <div class="absolute left-0 bottom-0 w-full h-1/2 bg-gradient-to-t from-black/50 to-transparent"></div>
    
    <div class="relative z-10 max-w-[1400px] mx-auto px-6 py-12 md:py-20 w-full">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 bg-secondary/10 backdrop-blur-md border border-secondary/20 px-4 py-1.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    <span class="text-secondary text-[10px] font-black uppercase tracking-widest">Live Season Tracking</span>
                </div>
                <h1 class="text-5xl md:text-8xl font-black text-white italic uppercase tracking-tighter leading-none drop-shadow-2xl">
                    Stats <span class="text-secondary">Centre</span>
                </h1>
                <p class="text-white/40 font-bold uppercase tracking-[0.3em] text-[10px] md:text-sm">2025/26 Season Performance Metrics</p>
            </div>

            <!-- Competition Info Card -->
            @if($activeCompetition)
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-6 pr-10 flex items-center gap-6 group hover:bg-white/10 transition duration-500">
                <div class="w-20 h-20 bg-white rounded-full p-3 shadow-2xl group-hover:scale-110 transition duration-500">
                    <img src="{{ $activeCompetition->logo_url ?? '/images/league-logo.png' }}" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="block text-white/40 text-[10px] font-black uppercase tracking-widest mb-1">Active Competition</span>
                    <h2 class="text-2xl font-black text-white italic uppercase leading-none">{{ $activeCompetition->name }}</h2>
                    <span class="inline-block mt-2 px-3 py-1 bg-secondary text-primary text-[8px] font-black rounded-lg uppercase italic">{{ $activeCompetition->type ?? 'Tournament' }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="container mx-auto px-6 pb-24">
    
    <!-- Player Stats Section -->
    <div class="space-y-24">
        
        <!-- Category Loop -->
        @php
            $playerCategories = [
                ['title' => 'Top Scorers', 'icon' => '⚽', 'data' => $topScorers, 'key' => 'goals_count', 'color' => 'secondary'],
                ['title' => 'Top Assists', 'icon' => '🎯', 'data' => $topAssists, 'key' => 'assists_count', 'color' => 'indigo-400'],
                ['title' => 'Yellow Cards', 'icon' => '🟨', 'data' => $topCards, 'key' => 'cards_count', 'color' => 'amber-400'],
                ['title' => 'Red Cards', 'icon' => '🟥', 'data' => $topRedCards ?? collect(), 'key' => 'red_cards_count', 'color' => 'rose-500'],
                ['title' => 'Clean Sheets', 'icon' => '🧤', 'data' => $topCleanSheets, 'key' => 'clean_sheets_count', 'color' => 'emerald-400'],
                ['title' => 'Man of the Match', 'icon' => '🏆', 'data' => $topMOTM, 'key' => 'motm_awards_count', 'color' => 'amber-400'],
            ];
        @endphp

        @foreach($playerCategories as $cat)
        @if(count($cat['data']) > 0)
        <div class="space-y-8">
            <!-- Section Header -->
            <div class="flex items-center gap-4">
                <div class="text-4xl">{{ $cat['icon'] }}</div>
                <div class="flex-1">
                    <h3 class="text-2xl md:text-3xl font-black text-primary uppercase italic tracking-tighter leading-none">{{ $cat['title'] }}</h3>
                    <div class="h-1 bg-{{ $cat['color'] }} w-20 mt-1"></div>
                </div>
            </div>

            <!-- Top 10 Compact Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-5 gap-4 md:gap-6">
                @foreach($cat['data']->take(10) as $index => $player)
                <div class="relative group">
                    <!-- Rank Badge -->
                    <div class="absolute -top-2 -left-2 w-8 h-8 rounded-lg {{ $index == 0 ? 'bg-secondary text-primary' : ($index < 3 ? 'bg-primary text-white' : 'bg-zinc-100 text-zinc-400') }} flex items-center justify-center text-xs font-black italic shadow-lg z-20 transform group-hover:-translate-y-1 transition-transform">
                        {{ $index + 1 }}
                    </div>

                    <div class="bg-white rounded-[1.5rem] overflow-hidden shadow-sm border border-zinc-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 h-full flex flex-col">
                        <!-- Compact Player Header -->
                        <div class="h-20 bg-zinc-50 relative overflow-hidden flex-shrink-0">
                            <div class="absolute inset-0 opacity-5 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-{{ $cat['color'] }} via-transparent to-transparent"></div>
                            @if($player->team->logo_url)
                            <img src="{{ $player->team->logo_url }}" class="absolute right-2 top-2 w-10 h-10 opacity-10 group-hover:scale-110 transition duration-500">
                            @endif
                        </div>

                        <!-- Player Profile -->
                        <div class="relative px-4 pb-4 -mt-12 flex flex-col items-center flex-1">
                            <div class="relative mb-3">
                                <div class="absolute inset-0 bg-{{ $cat['color'] }} rounded-full animate-pulse opacity-10 group-hover:scale-105 transition duration-500"></div>
                                @if($player->full_image_url)
                                    <img src="{{ $player->full_image_url }}" class="w-20 h-24 object-contain relative z-10 drop-shadow-md transform group-hover:scale-110 transition duration-500">
                                @else
                                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center border-2 border-zinc-50 relative z-10">
                                        <span class="text-xl font-black text-zinc-100">?</span>
                                    </div>
                                @endif
                            </div>

                            <div class="text-center space-y-1 mb-4 flex-1">
                                <h4 class="text-sm font-black text-primary uppercase italic tracking-tighter leading-tight group-hover:text-secondary transition-colors">{{ $player->name }}</h4>
                                <div class="flex items-center justify-center gap-1">
                                    <img src="{{ $player->team->logo_url }}" class="w-3 h-3 object-contain">
                                    <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">{{ $player->team->name }}</span>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-zinc-50 w-full text-center mt-auto">
                                <span class="text-3xl font-black text-{{ $cat['color'] }} italic">{{ $player->{$cat['key']} }}</span>
                                <span class="block text-[7px] font-black text-zinc-400 uppercase tracking-[0.2em] mt-1">{{ explode(' ', $cat['title'])[1] ?? 'Total' }}</span>
                            </div>
                        </div>

                        <a href="{{ route('player.details', $player->id) }}" class="block w-full py-2 bg-zinc-50 text-center text-[8px] font-black uppercase tracking-widest text-zinc-400 hover:bg-primary hover:text-white transition">Profile</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
        
    </div>

    <!-- Team Leaderboards Section -->
    <div class="mt-40 space-y-16">
        <div class="flex items-center gap-8">
            <h2 class="text-6xl md:text-8xl font-black text-primary uppercase italic tracking-tighter opacity-10 leading-none">Team Stats</h2>
            <div class="h-1 bg-secondary flex-1"></div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $teamStats = [
                    ['title' => 'Goals Scored', 'icon' => '⚽', 'data' => $topGoalsScored, 'key' => 'goals_for', 'color' => 'bg-green-600'],
                    ['title' => 'Total Shots', 'icon' => '🚀', 'data' => $topShots, 'key' => 'total_stat', 'color' => 'bg-indigo-600'],
                    ['title' => 'Corner Kicks', 'icon' => '🚩', 'data' => $topCorners, 'key' => 'total_stat', 'color' => 'bg-amber-600'],
                    ['title' => 'Team Discipline', 'icon' => '⚠️', 'data' => $topFouls, 'key' => 'total_stat', 'color' => 'bg-rose-600'],
                    ['title' => 'Offsides', 'icon' => '🏁', 'data' => $topOffsides, 'key' => 'total_stat', 'color' => 'bg-zinc-600'],
                    ['title' => 'GK Presence', 'icon' => '🧱', 'data' => $topSaves, 'key' => 'total_stat', 'color' => 'bg-cyan-600'],
                    ['title' => 'Missed Chances', 'icon' => '💨', 'data' => $topMissedChances, 'key' => 'total_stat', 'color' => 'bg-rose-600'],
                ];
            @endphp

            @foreach($teamStats as $tStat)
            @if(count($tStat['data']) > 0)
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b-4 border-zinc-100 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $tStat['icon'] }}</span>
                        <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">{{ $tStat['title'] }}</h3>
                    </div>
                    <div class="w-8 h-1 {{ $tStat['color'] }}"></div>
                </div>
                <div class="space-y-3">
                    @foreach($tStat['data'] as $index => $team)
                    <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-zinc-100 hover:border-secondary transition shadow-sm group">
                        <div class="flex items-center gap-4">
                            <span class="text-lg font-black text-zinc-200 italic w-4">{{ $index + 1 }}</span>
                            <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain group-hover:scale-110 transition">
                            <p class="font-black text-primary text-[10px] uppercase leading-none">{{ $team->name }}</p>
                        </div>
                        <span class="text-xl font-black text-primary italic">{{ $team->{$tStat['key']} }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>

<style>
    /* Add some custom classes for the colors if Tailwind doesn't generate them dynamically */
    .text-secondary { color: #00ff85; }
    .bg-secondary { background-color: #00ff85; }
    .from-secondary { --tw-gradient-from: #00ff85; }
    .bg-primary { background-color: #3d195b; }
    .text-primary { color: #3d195b; }
</style>
@endsection
