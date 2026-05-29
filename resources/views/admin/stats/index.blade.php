@extends('admin.layout')

@section('title', 'Tournament Stats')

@section('content')
<div class="space-y-12 pb-24">
    <!-- Competition Filter -->
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-100 dark:border-zinc-800 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-sm font-black text-primary dark:text-white uppercase tracking-widest italic">Statistical Analysis</h2>
            <p class="text-[10px] text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 font-bold uppercase tracking-tight">Viewing statistics for the selected competition</p>
        </div>
        <form action="{{ route('admin.stats.index') }}" method="GET" class="flex items-center gap-3">
            <select name="competition_id" onchange="this.form.submit()" class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-800 px-6 py-3 rounded-2xl font-bold text-xs text-primary dark:text-white focus:ring-2 focus:ring-secondary outline-none transition uppercase">
                @foreach($competitions as $comp)
                <option value="{{ $comp->id }}" {{ $competitionId == $comp->id ? 'selected' : '' }}>
                    {{ $comp->name }}
                </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Player Statistics -->
    <div class="space-y-6">
        <h2 class="text-xl font-black text-primary dark:text-white uppercase tracking-tighter flex items-center gap-3">
            <span class="w-2 h-8 bg-secondary rounded-full"></span>
            Player Performance
        </h2>
        
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Top Scorers -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400 dark:text-zinc-500 dark:text-zinc-400">Top Scorers</h3>
                    <span class="text-lg">⚽</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50/50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800">Rank</th>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800">Player</th>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800">Team</th>
                                <th class="px-6 py-3 text-right text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800 text-primary dark:text-white">Goals</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50 text-xs">
                            @foreach($topScorers as $index => $player)
                            <tr class="hover:bg-zinc-50 dark:bg-zinc-800/50/50 transition">
                                <td class="px-6 py-4 font-black text-zinc-300 dark:text-zinc-500 italic">#{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-primary dark:text-white">{{ $player->name }}</td>
                                <td class="px-6 py-4 text-zinc-500 dark:text-zinc-400">{{ $player->team->name }}</td>
                                <td class="px-6 py-4 text-right font-black text-primary dark:text-white">{{ $player->goals_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Assists -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400 dark:text-zinc-500 dark:text-zinc-400">Top Assists</h3>
                    <span class="text-lg">🎯</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50/50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800">Rank</th>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800">Player</th>
                                <th class="px-6 py-3 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800">Team</th>
                                <th class="px-6 py-3 text-right text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800 text-primary dark:text-white">Assists</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50 text-xs">
                            @foreach($topAssists as $index => $player)
                            <tr class="hover:bg-zinc-50 dark:bg-zinc-800/50/50 transition">
                                <td class="px-6 py-4 font-black text-zinc-300 dark:text-zinc-500 italic">#{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-primary dark:text-white">{{ $player->name }}</td>
                                <td class="px-6 py-4 text-zinc-500 dark:text-zinc-400">{{ $player->team->name }}</td>
                                <td class="px-6 py-4 text-right font-black text-primary dark:text-white">{{ $player->assists_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Clean Sheets -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400 dark:text-zinc-500 dark:text-zinc-400">Clean Sheets</h3>
                    <span class="text-lg">🧤</span>
                </div>
                <table class="w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($topCleanSheets as $index => $player)
                        <tr class="hover:bg-zinc-50 dark:bg-zinc-800/50/50 transition">
                            <td class="px-6 py-3 font-black text-zinc-300 dark:text-zinc-500 italic">#{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-bold text-primary dark:text-white">{{ $player->name }}</td>
                            <td class="px-6 py-3 text-right font-black text-primary dark:text-white">{{ $player->clean_sheets_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Discipline -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400 dark:text-zinc-500 dark:text-zinc-400">Cards</h3>
                    <span class="text-lg">🟨</span>
                </div>
                <table class="w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($topCards as $index => $player)
                        <tr class="hover:bg-zinc-50 dark:bg-zinc-800/50/50 transition">
                            <td class="px-6 py-3 font-black text-zinc-300 dark:text-zinc-500 italic">#{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-bold text-primary dark:text-white">{{ $player->name }}</td>
                            <td class="px-6 py-3 text-right font-black text-primary dark:text-white">{{ $player->cards_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Man of the Match -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400 dark:text-zinc-500 dark:text-zinc-400">MOTM Awards</h3>
                    <span class="text-lg">🏆</span>
                </div>
                <table class="w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($topMOTM as $index => $player)
                        <tr class="hover:bg-zinc-50 dark:bg-zinc-800/50/50 transition">
                            <td class="px-6 py-3 font-black text-zinc-300 dark:text-zinc-500 italic">#{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-bold text-primary dark:text-white">{{ $player->name }}</td>
                            <td class="px-6 py-3 text-right font-black text-primary dark:text-white">{{ $player->motm_awards_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Team Statistics -->
    <div class="space-y-6">
        <h2 class="text-xl font-black text-primary dark:text-white uppercase tracking-tighter flex items-center gap-3">
            <span class="w-2 h-8 bg-zinc-900 rounded-full"></span>
            Team Leaderboards
        </h2>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Goals Scored -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400 dark:text-zinc-500 dark:text-zinc-400">Goals Scored</h3>
                    <span class="text-lg">⚽</span>
                </div>
                <table class="w-full text-left border-collapse text-xs">
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($topGoalsScored as $index => $compTeam)
                        <tr class="hover:bg-zinc-50 dark:bg-zinc-800/50/50 transition">
                            <td class="px-6 py-3 font-black text-zinc-300 dark:text-zinc-500 italic">#{{ $index + 1 }}</td>
                            <td class="px-6 py-3 font-bold text-primary dark:text-white">{{ $compTeam->team->name }}</td>
                            <td class="px-6 py-3 text-right font-black text-green-600">{{ $compTeam->goals_for }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Other Team Stats Matrix -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-100 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-zinc-400 dark:text-zinc-500 dark:text-zinc-400">General Match Stats</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50/50">
                            <tr>
                                <th class="px-6 py-3 text-[9px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800">Stat</th>
                                <th class="px-6 py-3 text-[9px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800">#1 Team</th>
                                <th class="px-6 py-3 text-right text-[9px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-b border-zinc-100 dark:border-zinc-800">Total</th>
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
                            <tr class="hover:bg-zinc-50 dark:bg-zinc-800/50/50 transition">
                                <td class="px-6 py-4">
                                    <span class="flex items-center gap-2">
                                        <span>{{ $stat['emoji'] }}</span>
                                        <span class="font-bold text-zinc-600 dark:text-zinc-300 dark:text-zinc-500 uppercase tracking-tight">{{ $stat['label'] }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black text-primary dark:text-white">{{ $topTeam ? $topTeam->name : 'N/A' }}</td>
                                <td class="px-6 py-4 text-right font-black text-primary dark:text-white">{{ $topTeam ? $topTeam->total_stat : 0 }}</td>
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
