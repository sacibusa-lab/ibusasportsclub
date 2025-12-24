@extends('admin.layout')

@section('title', 'Tournament Stats')

@section('content')
<div class="space-y-12 pb-24">

    <!-- Player Statistics -->
    <div class="space-y-6">
        <h2 class="text-xl font-black text-primary uppercase tracking-tighter flex items-center gap-3">
            <span class="w-2 h-8 bg-secondary rounded-full"></span>
            Player Performance
        </h2>
        
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Top Scorers -->
            <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-100 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400">Top Scorers</h3>
                    <span class="text-lg">⚽</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-zinc-50/50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">Rank</th>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">Player</th>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">Team</th>
                                <th class="px-6 py-3 text-right text-[10px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100 text-primary">Goals</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50 text-xs">
                            @foreach($topScorers as $index => $player)
                            <tr class="hover:bg-zinc-50/50 transition">
                                <td class="px-6 py-4 font-black text-zinc-300 italic">#{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-primary">{{ $player->name }}</td>
                                <td class="px-6 py-4 text-zinc-500">{{ $player->team->name }}</td>
                                <td class="px-6 py-4 text-right font-black text-primary">{{ $player->goals_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Assists -->
            <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-100 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400">Top Assists</h3>
                    <span class="text-lg">🎯</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-zinc-50/50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">Rank</th>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">Player</th>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">Team</th>
                                <th class="px-6 py-3 text-right text-[10px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100 text-primary">Assists</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50 text-xs">
                            @foreach($topAssists as $index => $player)
                            <tr class="hover:bg-zinc-50/50 transition">
                                <td class="px-6 py-4 font-black text-zinc-300 italic">#{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-primary">{{ $player->name }}</td>
                                <td class="px-6 py-4 text-zinc-500">{{ $player->team->name }}</td>
                                <td class="px-6 py-4 text-right font-black text-primary">{{ $player->assists_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Clean Sheets -->
            <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-100 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400">Clean Sheets</h3>
                    <span class="text-lg">🧤</span>
                </div>
                <table class="w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($topCleanSheets as $index => $player)
                        <tr class="hover:bg-zinc-50/50 transition">
                            <td class="px-6 py-3 font-black text-zinc-300 italic">#{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-bold text-primary">{{ $player->name }}</td>
                            <td class="px-6 py-3 text-right font-black text-primary">{{ $player->clean_sheets_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Discipline -->
            <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-100 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400">Cards</h3>
                    <span class="text-lg">🟨</span>
                </div>
                <table class="w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($topCards as $index => $player)
                        <tr class="hover:bg-zinc-50/50 transition">
                            <td class="px-6 py-3 font-black text-zinc-300 italic">#{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-bold text-primary">{{ $player->name }}</td>
                            <td class="px-6 py-3 text-right font-black text-primary">{{ $player->cards_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Man of the Match -->
            <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-100 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400">MOTM Awards</h3>
                    <span class="text-lg">🏆</span>
                </div>
                <table class="w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($topMOTM as $index => $player)
                        <tr class="hover:bg-zinc-50/50 transition">
                            <td class="px-6 py-3 font-black text-zinc-300 italic">#{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-bold text-primary">{{ $player->name }}</td>
                            <td class="px-6 py-3 text-right font-black text-primary">{{ $player->motm_awards_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Team Statistics -->
    <div class="space-y-6">
        <h2 class="text-xl font-black text-primary uppercase tracking-tighter flex items-center gap-3">
            <span class="w-2 h-8 bg-zinc-900 rounded-full"></span>
            Team Leaderboards
        </h2>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Goals Scored -->
            <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-100 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400">Goals Scored</h3>
                    <span class="text-lg">⚽</span>
                </div>
                <table class="w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($topGoalsScored as $index => $team)
                        <tr class="hover:bg-zinc-50/50 transition">
                            <td class="px-6 py-3 font-black text-zinc-300 italic">#{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-bold text-primary">{{ $team->name }}</td>
                            <td class="px-6 py-3 text-right font-black text-green-600">{{ $team->goals_for }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Other Team Stats Matrix -->
            <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-100 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400">General Match Stats</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead class="bg-zinc-50/50">
                            <tr>
                                <th class="px-6 py-3 text-[9px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">Stat</th>
                                <th class="px-6 py-3 text-[9px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">#1 Team</th>
                                <th class="px-6 py-3 text-right text-[9px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50 text-[11px]">
                            @php
                                $stats = [
                                    ['label' => 'Total Shots', 'emoji' => '🚀', 'data' => $topShots],
                                    ['label' => 'Corner Kicks', 'emoji' => '🚩', 'data' => $topCorners],
                                    ['label' => 'Offsides', 'emoji' => '🏁', 'data' => $topOffsides],
                                    ['label' => 'Fouls Committed', 'emoji' => '⚠️', 'data' => $topFouls],
                                    ['label' => 'Throw-ins', 'emoji' => '👐', 'data' => $topThrows],
                                    ['label' => 'GK Saves', 'emoji' => '🧱', 'data' => $topSaves],
                                    ['label' => 'Goal Kicks', 'emoji' => '🦵', 'data' => $topGoalKicks],
                                    ['label' => 'Missed Chances', 'emoji' => '💨', 'data' => $topMissedChances],
                                ];
                            @endphp
                            @foreach($stats as $stat)
                            @php $topTeam = $stat['data']->first(); @endphp
                            <tr class="hover:bg-zinc-50/50 transition">
                                <td class="px-6 py-4">
                                    <span class="flex items-center gap-2">
                                        <span>{{ $stat['emoji'] }}</span>
                                        <span class="font-bold text-zinc-600 uppercase tracking-tight">{{ $stat['label'] }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black text-primary">{{ $topTeam ? $topTeam->name : 'N/A' }}</td>
                                <td class="px-6 py-4 text-right font-black text-primary">{{ $topTeam ? $topTeam->total_stat : 0 }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
