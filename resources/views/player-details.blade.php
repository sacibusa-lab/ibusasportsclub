@extends('layout')

@section('content')
<div class="space-y-12 pb-24">
    <!-- Hero Section -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-zinc-900 min-h-[500px] flex items-end">
        <!-- Dynamic Background Pattern -->
        <div class="absolute inset-0 opacity-20" style="background: radial-gradient(circle at 70% 30%, {{ $player->team->primary_color }} 0%, transparent 60%);"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-transparent"></div>
        
        <!-- Large Background Number -->
        <div class="absolute -right-12 -top-12 text-[25rem] font-black text-white/[0.03] select-none leading-none">
            {{ $player->shirt_number ?? '--' }}
        </div>

        <div class="container mx-auto px-6 relative z-10 flex flex-col md:flex-row items-end gap-12">
            <!-- Player Cutout Image -->
            <div class="w-full md:w-1/2 flex justify-center md:justify-end -mb-4">
                @if($player->full_image_url)
                    <img src="{{ $player->full_image_url }}" alt="{{ $player->name }}" class="max-h-[550px] w-auto drop-shadow-[0_20px_50px_rgba(0,0,0,0.5)] transform hover:scale-105 transition-transform duration-700">
                @elseif($player->image_url)
                    <img src="{{ $player->image_url }}" alt="{{ $player->name }}" class="max-h-[500px] w-auto rounded-3xl border-4 border-zinc-800 shadow-2xl mb-12">
                @else
                    <div class="h-[400px] w-[300px] bg-zinc-800/50 rounded-t-[3rem] flex items-center justify-center mb-12 border border-zinc-700">
                        <span class="text-zinc-600 text-9xl font-black">{{ substr($player->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>

            <!-- Player Info Info -->
            <div class="w-full md:w-1/2 mb-12 space-y-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <img src="{{ $player->team->logo_url }}" class="w-10 h-10 object-contain">
                        <span class="text-zinc-400 font-bold uppercase tracking-widest text-sm">{{ $player->team->name }}</span>
                    </div>
                    @php 
                        $names = explode(' ', $player->name);
                        $firstName = $names[0] ?? '';
                        $lastName = count($names) > 1 ? implode(' ', array_slice($names, 1)) : '';
                    @endphp
                    <div class="space-y-0">
                        <p class="text-2xl font-bold text-zinc-400 leading-none">{{ $firstName }}</p>
                        <h1 class="text-7xl md:text-8xl font-black text-white uppercase tracking-tighter leading-none">
                            {{ $lastName ?: $firstName }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-8">
                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/10">
                        <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest leading-none mb-1">Position</p>
                        <p class="text-xl font-black text-white uppercase">{{ $player->position }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/10">
                        <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest leading-none mb-1">Number</p>
                        <p class="text-xl font-black text-white">#{{ $player->shirt_number ?? '--' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-zinc-100 flex flex-col items-center justify-center text-center group hover:bg-zinc-50 transition-colors">
            <span class="text-4xl font-black text-primary mb-1 group-hover:scale-110 transition-transform">{{ $player->match_lineups_count }}</span>
            <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Appearances</span>
        </div>
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-zinc-100 flex flex-col items-center justify-center text-center group hover:bg-zinc-50 transition-colors">
            <span class="text-4xl font-black text-primary mb-1 group-hover:scale-110 transition-transform">{{ $player->goals_count }}</span>
            <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Goals</span>
        </div>
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-zinc-100 flex flex-col items-center justify-center text-center group hover:bg-zinc-50 transition-colors">
            <span class="text-4xl font-black text-primary mb-1 group-hover:scale-110 transition-transform">{{ $player->assists_count }}</span>
            <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Assists</span>
        </div>
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-zinc-100 flex flex-col items-center justify-center text-center group hover:bg-zinc-50 transition-colors">
            <span class="text-4xl font-black text-amber-500 mb-1 group-hover:scale-110 transition-transform">{{ $player->yellow_cards_count }}</span>
            <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-3.5 bg-amber-400 rounded-sm"></div>
                <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Yellows</span>
            </div>
        </div>
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-zinc-100 flex flex-col items-center justify-center text-center group hover:bg-zinc-50 transition-colors">
            <span class="text-4xl font-black text-rose-600 mb-1 group-hover:scale-110 transition-transform">{{ $player->red_cards_count }}</span>
            <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-3.5 bg-rose-600 rounded-sm"></div>
                <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Reds</span>
            </div>
        </div>
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-zinc-100 flex flex-col items-center justify-center text-center group hover:bg-zinc-50 transition-colors">
            <span class="text-4xl font-black text-secondary mb-1 group-hover:scale-110 transition-transform">{{ $player->motm_awards_count }}</span>
            <div class="flex items-center gap-1.5">
                <span class="text-sm">⭐</span>
                <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">MOTM</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Performance Breakdown -->
        <div class="lg:col-span-2 space-y-8">
            <div class="flex items-center gap-4">
                <h3 class="text-2xl font-black text-primary uppercase tracking-tight font-[Outfit]">Detailed Tournament Stats</h3>
                <div class="h-px bg-zinc-100 flex-1"></div>
            </div>

            <div class="bg-white rounded-3xl border border-zinc-100 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-50 border-b border-zinc-100">
                            <th class="px-8 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Metric</th>
                            <th class="px-8 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Season Record</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        <tr>
                            <td class="px-8 py-5">
                                <span class="block font-bold text-primary">Goal Contributions</span>
                                <span class="text-[10px] text-zinc-400 uppercase font-bold tracking-wide">Combined Goals + Assists</span>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-xl text-primary">{{ $player->goals_count + $player->assists_count }}</td>
                        </tr>
                        <tr>
                            <td class="px-8 py-5">
                                <span class="block font-bold text-primary">Efficiency</span>
                                <span class="text-[10px] text-zinc-400 uppercase font-bold tracking-wide">Goals per Appearance</span>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-xl text-primary">{{ $player->match_lineups_count > 0 ? number_format($player->goals_count / $player->match_lineups_count, 2) : '0.00' }}</td>
                        </tr>
                        <tr>
                            <td class="px-8 py-5">
                                <span class="block font-bold text-primary">Discipline Level</span>
                                <span class="text-[10px] text-zinc-400 uppercase font-bold tracking-wide">Total Cards Received</span>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-xl text-primary">
                                <span class="{{ ($player->yellow_cards_count + $player->red_cards_count) > 3 ? 'text-rose-600' : 'text-primary' }}">
                                    {{ $player->yellow_cards_count + $player->red_cards_count }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-8 py-5">
                                <span class="block font-bold text-primary">MOTM Achievements</span>
                                <span class="text-[10px] text-zinc-400 uppercase font-bold tracking-wide">Individual Excellence Awards</span>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-xl text-secondary">{{ $player->motm_awards_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Next Match Card -->
        <div class="space-y-8">
            <div class="flex items-center gap-4">
                <h3 class="text-2xl font-black text-primary uppercase tracking-tight">Next Fixture</h3>
                <div class="h-px bg-zinc-100 flex-1"></div>
            </div>

            @if($nextMatch)
            <div class="bg-primary rounded-[2.5rem] p-8 text-secondary relative overflow-hidden group shadow-xl">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
                
                <div class="relative z-10 space-y-8">
                    <div class="text-center">
                        <span class="inline-block bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                            {{ $nextMatch->match_date->format('l, j F') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex flex-col items-center gap-3 flex-1 text-center">
                            <img src="{{ $nextMatch->homeTeam->logo_url }}" class="w-20 h-20 object-contain drop-shadow-lg group-hover:scale-110 transition duration-500">
                            <span class="text-xs font-black uppercase tracking-tight">{{ $nextMatch->homeTeam->name }}</span>
                        </div>
                        
                        <div class="flex flex-col items-center">
                            <span class="text-3xl font-black text-white/20">VS</span>
                            <span class="bg-secondary text-primary px-3 py-1 rounded-lg text-[10px] font-black transform skew-x-[-12deg] mt-2">
                                {{ $nextMatch->match_date->format('H:i') }}
                            </span>
                        </div>

                        <div class="flex flex-col items-center gap-3 flex-1 text-center">
                            <img src="{{ $nextMatch->awayTeam->logo_url }}" class="w-20 h-20 object-contain drop-shadow-lg group-hover:scale-110 transition duration-500">
                            <span class="text-xs font-black uppercase tracking-tight">{{ $nextMatch->awayTeam->name }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/5 text-center">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1">Venue</p>
                        <p class="text-sm font-bold">{{ $nextMatch->homeTeam->stadium_name ?? 'Tournament Stadium' }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-zinc-50 rounded-[2.5rem] p-12 text-center border-2 border-dashed border-zinc-200">
                <span class="block text-4xl mb-4">🏠</span>
                <p class="text-zinc-500 font-bold uppercase tracking-widest text-xs">No Scheduled Matches</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
