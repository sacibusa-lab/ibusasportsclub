@extends('layout')

@section('title', 'Stats Centre')

@section('content')
<!-- Premium PL Header -->
<div class="relative overflow-hidden bg-primary mb-12 -mx-6 md:-mx-12">
    <div class="absolute inset-0 bg-gradient-to-br from-[#3d195b] via-[#3d195b] to-[#00ff85]/20"></div>
    <div class="absolute right-0 top-0 w-1/2 h-full bg-gradient-to-l from-[#04f5ff]/20 to-transparent"></div>
    
    <div class="relative z-10 max-w-[1400px] mx-auto px-6 py-8 md:py-12">
        <div class="flex items-center gap-6">
            <div class="h-1 bg-secondary w-24"></div>
            <h1 class="text-6xl font-black text-white italic uppercase tracking-tighter">Stats Centre</h1>
        </div>
        <p class="text-secondary/60 mt-4 font-bold uppercase tracking-widest text-sm">2025/26 Season Player Performance</p>
    </div>
</div>

<div class="container mx-auto px-6 pb-24">
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Goals -->
        @if(count($topScorers) > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-primary pb-4">
                <h3 class="text-2xl font-black text-primary uppercase italic tracking-tight">Goals</h3>
                <span class="text-2xl">⚽</span>
            </div>
            <div class="space-y-4">
                @foreach($topScorers as $index => $player)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100 hover:border-secondary transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        <div class="relative">
                            @if($player->image_url)
                            <img src="{{ $player->image_url }}" class="w-12 h-12 rounded-full object-cover border-2 border-zinc-50">
                            @else
                            <div class="w-12 h-12 bg-zinc-100 rounded-full flex items-center justify-center font-black text-zinc-400 text-xs">
                                {{ substr($player->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-black text-primary text-sm leading-none uppercase">{{ $player->name }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                @if($player->team->logo_url)
                                <img src="{{ $player->team->logo_url }}" class="w-3 h-3 object-contain opacity-80">
                                @endif
                                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-tight">{{ $player->team->name }}</p>
                            </div>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-primary italic">{{ $player->goals_count }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Assists -->
        @if(count($topAssists) > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-primary pb-4">
                <h3 class="text-2xl font-black text-primary uppercase italic tracking-tight">Assists</h3>
                <span class="text-2xl">🎯</span>
            </div>
            <div class="space-y-4">
                @foreach($topAssists as $index => $player)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100 hover:border-secondary transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        <div class="relative">
                            @if($player->image_url)
                            <img src="{{ $player->image_url }}" class="w-12 h-12 rounded-full object-cover border-2 border-zinc-50">
                            @else
                            <div class="w-12 h-12 bg-zinc-100 rounded-full flex items-center justify-center font-black text-zinc-400 text-xs">
                                {{ substr($player->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-black text-primary text-sm leading-none uppercase">{{ $player->name }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                @if($player->team->logo_url)
                                <img src="{{ $player->team->logo_url }}" class="w-3 h-3 object-contain opacity-80">
                                @endif
                                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-tight">{{ $player->team->name }}</p>
                            </div>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-primary italic">{{ $player->assists_count }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Clean Sheets -->
        @if(count($topCleanSheets) > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-primary pb-4">
                <h3 class="text-2xl font-black text-primary uppercase italic tracking-tight">Clean Sheets</h3>
                <span class="text-2xl">🧤</span>
            </div>
            <div class="space-y-4">
                @foreach($topCleanSheets as $index => $player)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100 hover:border-secondary transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        <div class="relative">
                            @if($player->image_url)
                            <img src="{{ $player->image_url }}" class="w-12 h-12 rounded-full object-cover border-2 border-zinc-50">
                            @else
                            <div class="w-12 h-12 bg-zinc-100 rounded-full flex items-center justify-center font-black text-zinc-400 text-xs">
                                {{ substr($player->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-black text-primary text-sm leading-none uppercase">{{ $player->name }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                @if($player->team->logo_url)
                                <img src="{{ $player->team->logo_url }}" class="w-3 h-3 object-contain opacity-80">
                                @endif
                                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-tight">{{ $player->team->name }}</p>
                            </div>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-primary italic">{{ $player->clean_sheets_count }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Discipline -->
        @if(count($topCards) > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-primary pb-4">
                <h3 class="text-2xl font-black text-primary uppercase italic tracking-tight">Discipline</h3>
                <span class="text-2xl">🟨</span>
            </div>
            <div class="space-y-4">
                @foreach($topCards as $index => $player)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100 hover:border-secondary transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        <div class="relative">
                            @if($player->image_url)
                            <img src="{{ $player->image_url }}" class="w-12 h-12 rounded-full object-cover border-2 border-zinc-50">
                            @else
                            <div class="w-12 h-12 bg-zinc-100 rounded-full flex items-center justify-center font-black text-zinc-400 text-xs">
                                {{ substr($player->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-black text-primary text-sm leading-none uppercase">{{ $player->name }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                @if($player->team->logo_url)
                                <img src="{{ $player->team->logo_url }}" class="w-3 h-3 object-contain opacity-80">
                                @endif
                                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-tight">{{ $player->team->name }}</p>
                            </div>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-primary italic">{{ $player->cards_count }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Man of the Match -->
        @if(count($topMOTM) > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-secondary pb-4">
                <h3 class="text-2xl font-black text-primary uppercase italic tracking-tight font-body">Man of the Match</h3>
                <span class="text-2xl">🏆</span>
            </div>
            <div class="space-y-4">
                @foreach($topMOTM as $index => $player)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100 hover:border-secondary transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        <div class="relative">
                            @if($player->image_url)
                            <img src="{{ $player->image_url }}" class="w-12 h-12 rounded-full object-cover border-2 border-zinc-50">
                            @else
                            <div class="w-12 h-12 bg-zinc-100 rounded-full flex items-center justify-center font-black text-zinc-400 text-xs">
                                {{ substr($player->name, 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-black text-primary text-sm leading-none uppercase">{{ $player->name }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                @if($player->team->logo_url)
                                <img src="{{ $player->team->logo_url }}" class="w-3 h-3 object-contain opacity-80">
                                @endif
                                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-tight">{{ $player->team->name }}</p>
                            </div>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-primary italic">{{ $player->motm_awards_count }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <!-- Team Leaderboards Header -->
    <div class="mt-24 mb-12 flex items-center gap-6">
        <div class="h-1 bg-secondary w-24"></div>
        <h2 class="text-4xl font-black text-primary uppercase italic tracking-tighter">Team Leaderboards</h2>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Goals Scored -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-green-600 pb-4">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">Goals Scored</h3>
                <span class="text-xl">⚽</span>
            </div>
            <div class="space-y-4">
                @foreach($topGoalsScored as $index => $team)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100 hover:border-green-500 transition-all">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                        @endif
                        <p class="font-black text-primary text-xs uppercase">{{ $team->name }}</p>
                    </div>
                    <span class="text-xl font-black text-green-600 italic">{{ $team->goals_for }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Most Shots -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-indigo-600 pb-4">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">Most Shots</h3>
                <span class="text-xl">🚀</span>
            </div>
            <div class="space-y-4">
                @foreach($topShots as $index => $team)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                        @endif
                        <p class="font-black text-primary text-xs uppercase">{{ $team->name }}</p>
                    </div>
                    <span class="text-xl font-black text-primary italic">{{ $team->total_stat }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Most Corners -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-indigo-600 pb-4">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">Corner Kicks</h3>
                <span class="text-xl">🚩</span>
            </div>
            <div class="space-y-4">
                @foreach($topCorners as $index => $team)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                        @endif
                        <p class="font-black text-primary text-xs uppercase">{{ $team->name }}</p>
                    </div>
                    <span class="text-xl font-black text-primary italic">{{ $team->total_stat }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Most Fouls -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-rose-600 pb-4">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">Fouls</h3>
                <span class="text-xl">⚠️</span>
            </div>
            <div class="space-y-4">
                @foreach($topFouls as $index => $team)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                        @endif
                        <p class="font-black text-primary text-xs uppercase">{{ $team->name }}</p>
                    </div>
                    <span class="text-xl font-black text-primary italic">{{ $team->total_stat }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Offsides -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-indigo-600 pb-4">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">Offsides</h3>
                <span class="text-xl">🏁</span>
            </div>
            <div class="space-y-4">
                @foreach($topOffsides as $index => $team)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                        @endif
                        <p class="font-black text-primary text-xs uppercase">{{ $team->name }}</p>
                    </div>
                    <span class="text-xl font-black text-primary italic">{{ $team->total_stat }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Throw-ins -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-indigo-600 pb-4">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">Throw-ins</h3>
                <span class="text-xl">👐</span>
            </div>
            <div class="space-y-4">
                @foreach($topThrows as $index => $team)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                        @endif
                        <p class="font-black text-primary text-xs uppercase">{{ $team->name }}</p>
                    </div>
                    <span class="text-xl font-black text-primary italic">{{ $team->total_stat }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- GK Saves -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-indigo-600 pb-4">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">GK Saves</h3>
                <span class="text-xl">🧱</span>
            </div>
            <div class="space-y-4">
                @foreach($topSaves as $index => $team)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                        @endif
                        <p class="font-black text-primary text-xs uppercase">{{ $team->name }}</p>
                    </div>
                    <span class="text-xl font-black text-primary italic">{{ $team->total_stat }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Goal Kicks -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-indigo-600 pb-4">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">Goal Kicks</h3>
                <span class="text-xl">🦵</span>
            </div>
            <div class="space-y-4">
                @foreach($topGoalKicks as $index => $team)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                        @endif
                        <p class="font-black text-primary text-xs uppercase">{{ $team->name }}</p>
                    </div>
                    <span class="text-xl font-black text-primary italic">{{ $team->total_stat }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Missed Chances -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b-4 border-rose-600 pb-4">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tight">Missed Chances</h3>
                <span class="text-xl">💨</span>
            </div>
            <div class="space-y-4">
                @foreach($topMissedChances as $index => $team)
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-zinc-100">
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-black text-zinc-300 italic w-6">{{ $index + 1 }}</span>
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                        @endif
                        <p class="font-black text-primary text-xs uppercase">{{ $team->name }}</p>
                    </div>
                    <span class="text-xl font-black text-rose-600 italic">{{ $team->total_stat }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
