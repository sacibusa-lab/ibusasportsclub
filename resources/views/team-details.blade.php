@extends('layout')

@section('content')
<div class="space-y-12">
    <!-- Hero Section -->
    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
        <div class="absolute inset-0 bg-zinc-900">
            <div class="absolute inset-0 opacity-80" style="background-color: {{ $team->primary_color ?? '#000000' }}"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20"></div>
        </div>

        <div class="relative p-8 md:p-16 flex flex-col md:flex-row items-center gap-10">
            <!-- Large Logo -->
            <div class="w-40 h-40 md:w-56 md:h-56 bg-white rounded-full flex items-center justify-center shadow-2xl p-4 transform hover:scale-105 transition duration-500">
                @if($team->logo_url)
                <img src="{{ $team->logo_url }}" class="w-full h-full object-contain">
                @else
                <span class="text-4xl font-black text-zinc-300">{{ substr($team->name, 0, 1) }}</span>
                @endif
            </div>

            <!-- Team Info -->
            <div class="text-center md:text-left space-y-4 flex-1">
                <div class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-black uppercase tracking-widest mb-2">
                    {{ $team->group->name }}
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white uppercase tracking-tighter leading-none italic drop-shadow-lg">
                    {{ $team->name }}
                </h1>
                <div class="flex items-center justify-center md:justify-start gap-6 text-white/90">
                    <div class="flex items-center gap-2">
                        <img src="/images/stadium-icon.png" class="w-5 h-5 brightness-0 invert">
                        <span class="font-bold text-lg">{{ $team->stadium_name ?? 'Stadium TBD' }}</span>
                    </div>
                    @if($team->manager)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="font-bold text-lg">Mgr. {{ $team->manager }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="hidden lg:grid grid-cols-2 gap-4 w-64">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 text-center border border-white/10">
                    <span class="block text-3xl font-black text-white">{{ $team->points }}</span>
                    <span class="text-[10px] text-white/60 uppercase tracking-widest font-bold">Points</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 text-center border border-white/10">
                    <span class="block text-3xl font-black text-white">#{{ $rank }}</span>
                    <span class="text-[10px] text-white/60 uppercase tracking-widest font-bold">Rank</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 text-center border border-white/10 col-span-2 flex justify-between px-6">
                    <div class="text-center">
                        <span class="block text-xl font-black text-green-400">{{ $team->wins }}</span>
                        <span class="text-[8px] text-white/60 uppercase font-bold">W</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-xl font-black text-zinc-400">{{ $team->draws }}</span>
                        <span class="text-[8px] text-white/60 uppercase font-bold">D</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-xl font-black text-red-400">{{ $team->losses }}</span>
                        <span class="text-[8px] text-white/60 uppercase font-bold">L</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main Content: Squad -->
        <div class="lg:col-span-2 space-y-12">
            <h3 class="text-3xl font-black text-primary uppercase tracking-tighter flex items-center gap-3 italic">
                The Squad
                <span class="bg-zinc-100 text-zinc-500 text-xs px-3 py-1 rounded-full not-italic">{{ $team->players->count() }} Players</span>
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
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <h4 class="text-sm font-black text-zinc-400 uppercase tracking-widest">{{ $positionNames[$pos] ?? $pos }}</h4>
                            <div class="h-px bg-zinc-100 flex-1"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($squad[$pos] as $player)
                            <a href="{{ route('player.details', $player->id) }}" class="bg-white rounded-2xl border border-zinc-100 flex flex-col hover:shadow-xl transition-all duration-500 group overflow-hidden relative">
                                <!-- Background Team Color Accent -->
                                <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 rounded-full opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none" style="background-color: {{ $team->primary_color }}"></div>
                                
                                <div class="p-5 flex items-start gap-4 flex-1">
                                    <!-- Player Image (Full Image if available, else Avatar) -->
                                    <div class="relative w-20 h-24 flex-none">
                                        @if($player->full_image_url)
                                            <img src="{{ $player->full_image_url }}" class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-full object-contain z-10 drop-shadow-lg group-hover:scale-110 transition-transform duration-500">
                                            <div class="absolute inset-0 bg-zinc-50 rounded-xl"></div>
                                        @elseif($player->image_url)
                                            <img src="{{ $player->image_url }}" class="w-full h-full object-cover rounded-xl border border-zinc-50 grayscale group-hover:grayscale-0 transition-all duration-500">
                                        @else
                                            <div class="w-full h-full bg-zinc-50 rounded-xl flex items-center justify-center font-black text-zinc-200 text-3xl">
                                                {{ substr($player->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="absolute -bottom-2 -left-2 bg-white text-primary text-[10px] font-black px-2 py-1 rounded-lg border border-zinc-100 shadow-sm z-20">#{{ $player->shirt_number ?? '--' }}</span>
                                    </div>

                                    <div class="flex-1 pt-1">
                                        <h4 class="font-black text-primary uppercase text-sm leading-tight group-hover:text-secondary transition-colors mb-2">{{ $player->name }}</h4>
                                        <div class="flex flex-wrap gap-1.5">
                                            <span class="text-[8px] font-black bg-zinc-50 text-zinc-400 px-2 py-1 rounded-md uppercase tracking-widest">{{ $player->position }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stats Bar -->
                                <div class="bg-zinc-50 px-5 py-3 flex justify-between items-center border-t border-zinc-100">
                                    <div class="flex gap-4">
                                        <div class="text-center">
                                            <span class="block text-[10px] font-black text-primary leading-none">{{ $player->match_lineups_count }}</span>
                                            <span class="text-[7px] font-bold text-zinc-400 uppercase tracking-tighter">Apps</span>
                                        </div>
                                        <div class="text-center">
                                            <span class="block text-[10px] font-black text-primary leading-none">{{ $player->goals_count }}</span>
                                            <span class="text-[7px] font-bold text-zinc-400 uppercase tracking-tighter">Goals</span>
                                        </div>
                                        <div class="text-center">
                                            <span class="block text-[10px] font-black text-primary leading-none">{{ $player->assists_count }}</span>
                                            <span class="text-[7px] font-bold text-zinc-400 uppercase tracking-tighter">Asst</span>
                                        </div>
                                    </div>
                                    <div class="w-6 h-6 rounded-lg bg-white border border-zinc-200 flex items-center justify-center text-zinc-300 group-hover:bg-primary group-hover:text-white transition-all transform group-hover:rotate-45">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
            <div class="bg-zinc-50 rounded-xl p-12 text-center border border-zinc-100 border-dashed">
                <span class="text-zinc-400 font-bold">No players registered yet.</span>
            </div>
            @endif
        </div>

        <!-- Sidebar: Matches -->
        <div class="space-y-12">
            <!-- Next Match -->
            @if($nextMatch)
            <div class="space-y-4">
                <h3 class="text-xl font-black text-primary uppercase tracking-tighter">Next Match</h3>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-zinc-100 border-l-4 border-l-secondary relative overflow-hidden">
                    <div class="flex flex-col items-center text-center space-y-4 relative z-10">
                        <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($nextMatch->match_date)->format('D, M j • H:i') }}</span>
                        
                        <div class="flex items-center justify-between w-full gap-2">
                            <div class="flex flex-col items-center w-1/3">
                                <img src="{{ $nextMatch->homeTeam->logo_url ?? '' }}" class="w-12 h-12 object-contain mb-2">
                                <span class="font-bold text-xs leading-tight">{{ $nextMatch->homeTeam->name }}</span>
                            </div>
                            <span class="font-black text-2xl text-zinc-300 italic">VS</span>
                            <div class="flex flex-col items-center w-1/3">
                                <img src="{{ $nextMatch->awayTeam->logo_url ?? '' }}" class="w-12 h-12 object-contain mb-2">
                                <span class="font-bold text-xs leading-tight">{{ $nextMatch->awayTeam->name }}</span>
                            </div>
                        </div>

                        <a href="{{ route('match.details', $nextMatch->id) }}" class="w-full py-3 bg-zinc-900 text-white font-bold rounded-xl text-xs uppercase tracking-widest hover:bg-primary transition">Match Center</a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Results -->
            <div class="space-y-4">
                <h3 class="text-xl font-black text-primary uppercase tracking-tighter">Recent Form</h3>
                <div class="space-y-2">
                    @foreach($recentMatches as $match)
                    @php
                        $isHome = $match->home_team_id == $team->id;
                        $teamScore = $isHome ? $match->home_score : $match->away_score;
                        $opponentScore = $isHome ? $match->away_score : $match->home_score;
                        $resultColor = $teamScore > $opponentScore ? 'bg-green-500' : ($teamScore == $opponentScore ? 'bg-zinc-400' : 'bg-red-500');
                        $resultChar = $teamScore > $opponentScore ? 'W' : ($teamScore == $opponentScore ? 'D' : 'L');
                    @endphp
                    <a href="{{ route('match.details', $match->id) }}" class="group bg-white rounded-xl p-4 border border-zinc-100 flex items-center justify-between hover:border-secondary transition">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full {{ $resultColor }} flex items-center justify-center text-white text-[10px] font-black">
                                {{ $resultChar }}
                            </div>
                            <div class="text-xs font-bold text-zinc-500">
                                vs <span class="text-primary">{{ $isHome ? $match->awayTeam->name : $match->homeTeam->name }}</span>
                            </div>
                        </div>
                        <div class="font-black text-primary text-sm bg-zinc-50 px-3 py-1 rounded-lg group-hover:bg-secondary group-hover:text-white transition">
                            {{ $match->home_score }} - {{ $match->away_score }}
                        </div>
                    </a>
                    @endforeach
                    @if($recentMatches->isEmpty())
                    <div class="text-center text-zinc-400 text-xs font-bold py-4 italic">No recent matches played.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
