@extends('layout')
@section('content')
<!-- Premium PL Header -->
<div class="relative overflow-hidden bg-primary mb-8 -mx-4 sm:-mx-6 md:-mx-12">
    <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary to-secondary/20"></div>
    <div class="absolute right-0 top-0 w-1/2 h-full bg-gradient-to-l from-secondary/10 to-transparent"></div>
    
    <div class="relative z-10 max-w-[1400px] mx-auto px-4 sm:px-6 py-8 md:py-12">
        <h1 class="text-4xl md:text-6xl lg:text-8xl font-black text-white italic uppercase tracking-tighter leading-none">Table</h1>
    </div>
</div>

<div class="max-w-[1400px] mx-auto space-y-8 md:space-y-12 pb-12 md:pb-24">
    

    <!-- Filter Bar Emulation -->
    <div class="flex flex-wrap items-center gap-2 md:gap-3 bg-white p-3 md:p-4 rounded-xl border border-zinc-100 shadow-sm">
        <div class="px-3 py-1.5 md:px-4 md:py-2 bg-zinc-50 rounded-lg border border-zinc-200 text-[9px] md:text-[11px] font-black text-primary uppercase tracking-widest flex items-center gap-2 cursor-pointer hover:bg-zinc-100 transition">
            {{ $siteSettings['site_name'] }} <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div class="px-3 py-1.5 md:px-4 md:py-2 bg-zinc-50 rounded-lg border border-zinc-200 text-[9px] md:text-[11px] font-black text-primary uppercase tracking-widest flex items-center gap-2 cursor-pointer hover:bg-zinc-100 transition">
            {{ $siteSettings['current_season'] ?? date('Y') }} <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <button class="ml-auto text-[9px] font-black text-zinc-400 hover:text-primary transition uppercase tracking-widest flex items-center gap-2">
            Reset <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </button>
    </div>

    @foreach($standings as $groupName => $teams)
    <div class="space-y-4 md:space-y-6">
        <h2 class="text-xl md:text-2xl font-black text-primary uppercase italic tracking-tighter border-l-4 border-secondary pl-4">{{ $groupName }}</h2>
        
        <div class="relative">
            <!-- Scroll Indicator Mobile -->
            <div class="md:hidden absolute -top-4 right-0 flex items-center gap-1 text-[8px] font-black text-zinc-300 uppercase tracking-widest">
                <span>Scroll</span>
                <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-zinc-100 overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse min-w-[600px] md:min-w-0">
                    <thead>
                        <tr class="text-zinc-400 text-[9px] md:text-[10px] font-black uppercase tracking-widest border-b border-zinc-100">
                            <th class="px-4 py-4 md:px-6 md:py-5 text-center w-12 md:w-16">Pos</th>
                            <th class="px-4 py-4 md:px-6 md:py-5">Team</th>
                            <th class="px-2 py-4 md:px-4 md:py-5 text-center">Pl</th>
                            <th class="px-2 py-4 md:px-4 md:py-5 text-center">W</th>
                            <th class="px-2 py-4 md:px-4 md:py-5 text-center">D</th>
                            <th class="px-2 py-4 md:px-4 md:py-5 text-center">L</th>
                            <th class="px-2 py-4 md:px-4 md:py-5 text-center hidden sm:table-cell">GF</th>
                            <th class="px-2 py-4 md:px-4 md:py-5 text-center hidden sm:table-cell">GA</th>
                            <th class="px-2 py-4 md:px-4 md:py-5 text-center">GD</th>
                            <th class="px-4 py-4 md:px-6 md:py-5 text-center bg-zinc-50/50">Pts</th>
                            <th class="px-4 py-4 md:px-6 md:py-5 text-center">Form</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($teams as $index => $team)
                        <tr class="hover:bg-zinc-50/80 transition-colors group">
                            <td class="relative px-4 py-4 md:px-6 md:py-5 text-center">
                                @if($index < 2)
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-secondary shadow-[0_0_10px_rgba(0,255,133,0.5)]"></div>
                                @endif
                                <span class="text-xs md:text-sm font-black text-primary italic">{{ $index + 1 }}</span>
                            </td>
                            <td class="px-4 py-4 md:px-6 md:py-5">
                                <div class="flex items-center gap-3 md:gap-4">
                                    @if($team->logo_url)
                                    <img src="{{ $team->logo_url }}" class="w-6 h-6 md:w-8 md:h-8 object-contain">
                                    @else
                                    <div class="w-6 h-6 md:w-8 md:h-8 bg-zinc-100 rounded-full flex items-center justify-center font-black text-zinc-400 text-[9px] uppercase">
                                        {{ substr($team->name, 0, 1) }}
                                    </div>
                                    @endif
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-primary text-sm md:text-base tracking-tight uppercase group-hover:text-secondary transition-colors truncate max-w-[120px] md:max-w-none">{{ $team->name }}</span>
                                            @if($team->is_playing)
                                            <span class="bg-rose-500 text-white text-[6px] md:text-[7px] font-black px-1.5 py-0.5 rounded animate-pulse uppercase tracking-widest">LIVE</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 py-4 md:px-4 md:py-5 text-center text-xs md:text-sm font-bold text-zinc-500">{{ $team->played }}</td>
                            <td class="px-2 py-4 md:px-4 md:py-5 text-center text-xs md:text-sm font-bold text-zinc-500">{{ $team->wins }}</td>
                            <td class="px-2 py-4 md:px-4 md:py-5 text-center text-xs md:text-sm font-bold text-zinc-500">{{ $team->draws }}</td>
                            <td class="px-2 py-4 md:px-4 md:py-5 text-center text-xs md:text-sm font-bold text-zinc-500">{{ $team->losses }}</td>
                            <td class="px-2 py-4 md:px-4 md:py-5 text-center text-xs md:text-sm font-bold text-zinc-500 hidden sm:table-cell">{{ $team->goals_for }}</td>
                            <td class="px-2 py-4 md:px-4 md:py-5 text-center text-xs md:text-sm font-bold text-zinc-500 hidden sm:table-cell">{{ $team->goals_against }}</td>
                            <td class="px-2 py-4 md:px-4 md:py-5 text-center text-xs md:text-sm font-bold text-zinc-500">{{ $team->goal_difference > 0 ? '+' : '' }}{{ $team->goal_difference }}</td>
                            <td class="px-4 py-4 md:px-6 md:py-5 text-center font-black text-primary text-sm md:text-base bg-zinc-50/30">{{ $team->points }}</td>
                            <td class="px-4 py-4 md:px-6 md:py-5 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @foreach(array_slice($team->form, 0, 3) as $result)
                                    <div class="w-4 h-4 md:w-5 md:h-5 rounded-full flex items-center justify-center text-[8px] font-black text-white
                                        {{ $result == 'W' ? 'bg-[#00ff85]' : ($result == 'L' ? 'bg-[#ff005a]' : 'bg-zinc-400') }}">
                                        {{ $result }}
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-zinc-50 px-4 py-3 md:px-8 md:py-4 rounded-b-2xl border-x border-b border-zinc-100 flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 bg-secondary rounded-sm"></div>
                    <span class="text-[8px] md:text-[9px] font-black text-zinc-400 uppercase tracking-widest">Qualification</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection
