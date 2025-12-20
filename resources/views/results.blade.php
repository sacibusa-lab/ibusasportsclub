@extends('layout')

@section('content')
<div class="max-w-5xl mx-auto space-y-12">
    <!-- Header & Filters -->
    <div class="text-center space-y-8">
        <div class="space-y-2">
            <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em]">Season {{ $siteSettings['current_season'] ?? date('Y') }}</h2>
            <div class="h-1 w-20 bg-primary mx-auto"></div>
        </div>
        
        <form action="{{ route('results') }}" method="GET" class="flex flex-wrap items-center justify-center gap-4">
            <div class="relative">
                <select name="season" class="appearance-none bg-zinc-50 border border-zinc-200 px-8 py-3 rounded-full font-bold text-[10px] uppercase tracking-widest outline-none focus:border-primary transition cursor-pointer pr-12">
                    <option value="2025-2026">2025-2026</option>
                </select>
                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <div class="relative">
                <select name="matchday" onchange="this.form.submit()" class="appearance-none bg-zinc-50 border border-zinc-200 px-8 py-3 rounded-full font-bold text-[10px] uppercase tracking-widest outline-none focus:border-primary transition cursor-pointer pr-12 min-w-[160px]">
                    <option value="">All Matchdays</option>
                    @foreach($matchdays as $md)
                    <option value="{{ $md }}" {{ $matchdayId == $md ? 'selected' : '' }}>Matchday {{ $md }}</option>
                    @endforeach
                </select>
                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <div class="relative">
                <select name="team" onchange="this.form.submit()" class="appearance-none bg-zinc-50 border border-zinc-200 px-8 py-3 rounded-full font-bold text-[10px] uppercase tracking-widest outline-none focus:border-primary transition cursor-pointer pr-12 min-w-[200px]">
                    <option value="">All Clubs</option>
                    @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ $teamId == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                    @endforeach
                </select>
                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </form>
    </div>

    @forelse($resultsGrouped as $date => $matches)
    <div class="space-y-6">
        <!-- Date Header -->
        <div class="flex items-center justify-between border-b border-zinc-100 pb-4">
            <h3 class="font-black text-primary uppercase italic tracking-tighter text-2xl">{{ \Carbon\Carbon::parse($date)->format('l') }}</h3>
            <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($date)->format('d F') }}</span>
        </div>

        <!-- Matches Table -->
        <div class="bg-white divide-y divide-zinc-100 border border-zinc-100 rounded-2xl overflow-hidden shadow-sm">
            @foreach($matches as $match)
            <a href="{{ route('match.details', $match->id) }}" class="flex items-center gap-4 py-6 px-6 hover:bg-zinc-50 transition-colors group">
                <!-- Left Icon/Time -->
                <div class="w-12 shrink-0 flex items-center justify-center">
                    <div class="w-8 h-8 rounded-full bg-zinc-50 flex items-center justify-center group-hover:bg-primary transition-colors">
                        <svg class="w-4 h-4 text-zinc-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15l-3-3 3-3m0 6l3-3-3-3"/></svg>
                    </div>
                </div>

                <!-- Teams & Score Container -->
                <div class="flex-1 flex items-center justify-center gap-4 md:gap-8">
                    <!-- Home Team -->
                    <div class="flex-1 flex items-center justify-end gap-3 text-right">
                        <span class="hidden md:block font-bold text-primary text-sm uppercase">{{ $match->homeTeam->name }}</span>
                        @if($match->homeTeam->logo_url)
                        <img src="{{ $match->homeTeam->logo_url }}" class="w-10 h-10 object-contain">
                        @else
                        <div class="w-10 h-10 bg-zinc-50 rounded-lg flex items-center justify-center font-black text-[10px] text-zinc-300">{{ substr($match->homeTeam->name, 0, 1) }}</div>
                        @endif
                        <span class="font-black text-zinc-300 text-[11px] uppercase tracking-tighter w-8 text-center">{{ $match->homeTeam->short_name ?? strtoupper(substr($match->homeTeam->name, 0, 3)) }}</span>
                    </div>

                    <!-- Score Box -->
                    <div class="flex items-center gap-1 shrink-0 px-4 bg-zinc-50/50 py-2 rounded-xl border border-zinc-100/50">
                        <div class="w-10 h-12 bg-white rounded-lg flex items-center justify-center font-black text-primary text-2xl shadow-sm border border-zinc-200/50">{{ $match->home_score }}</div>
                        <div class="w-10 h-12 bg-white rounded-lg flex items-center justify-center font-black text-primary text-2xl shadow-sm border border-zinc-200/50">{{ $match->away_score }}</div>
                    </div>

                    <!-- Away Team -->
                    <div class="flex-1 flex items-center justify-start gap-4 md:gap-3">
                        <span class="font-black text-zinc-300 text-[11px] uppercase tracking-tighter w-8 text-center">{{ $match->awayTeam->short_name ?? strtoupper(substr($match->awayTeam->name, 0, 3)) }}</span>
                        @if($match->awayTeam->logo_url)
                        <img src="{{ $match->awayTeam->logo_url }}" class="w-10 h-10 object-contain">
                        @else
                        <div class="w-10 h-10 bg-zinc-50 rounded-lg flex items-center justify-center font-black text-[10px] text-zinc-300">{{ substr($match->awayTeam->name, 0, 1) }}</div>
                        @endif
                        <span class="hidden md:block font-bold text-primary text-sm uppercase">{{ $match->awayTeam->name }}</span>
                    </div>
                </div>

                <!-- Right Side Broadcast/Info -->
                <div class="w-24 shrink-0 flex items-center justify-end">
                    @if($match->broadcaster_logo)
                    <img src="{{ $match->broadcaster_logo }}" class="h-5 object-contain opacity-40 group-hover:opacity-100 transition-opacity">
                    @else
                    <div class="text-[8px] font-black text-zinc-300 uppercase tracking-widest text-right leading-none group-hover:text-primary transition-colors">
                        Full<br>Report
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @empty
    <div class="py-20 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-zinc-100">
        <div class="w-16 h-16 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-zinc-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <span class="text-zinc-300 font-black uppercase tracking-widest text-[10px]">No results found matching your criteria.</span>
    </div>
    @endforelse

    <!-- Novelty Matches Section -->
    @if($noveltyResults->count() > 0)
    <div class="pt-20 space-y-12 border-t border-zinc-100">
        <div class="text-center space-y-3">
            <h3 class="text-3xl font-black text-accent uppercase italic tracking-tighter">Novelty exhibition</h3>
            <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-[0.4em] italic">Beyond the League Points</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            @foreach($noveltyResults as $match)
            <a href="{{ route('match.details', $match->id) }}" class="relative overflow-hidden bg-white border border-zinc-100 rounded-[2.5rem] p-10 hover:border-accent transition-all group shadow-sm">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-accent/5 rounded-full blur-2xl"></div>
                
                <div class="flex items-center justify-between mb-10">
                    <span class="text-[8px] font-black text-white bg-accent px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-accent/20">Novelty</span>
                    <span class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest">{{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y') }}</span>
                </div>
                
                <div class="flex items-center justify-center gap-6 mb-10 text-center">
                    <div class="flex-1 flex flex-col items-center gap-4">
                        <img src="{{ $match->homeTeam->logo_url }}" class="w-16 h-16 object-contain group-hover:scale-110 transition-transform duration-500">
                        <span class="text-[11px] font-black text-primary uppercase w-full line-clamp-1">{{ $match->homeTeam->name }}</span>
                    </div>
                    <div class="shrink-0 flex items-center gap-3">
                        <span class="text-5xl font-black text-primary italic tracking-tighter">{{ $match->home_score }}</span>
                        <div class="w-1 h-8 bg-zinc-100 rounded-full"></div>
                        <span class="text-5xl font-black text-primary italic tracking-tighter">{{ $match->away_score }}</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-4">
                        <img src="{{ $match->awayTeam->logo_url }}" class="w-16 h-16 object-contain group-hover:scale-110 transition-transform duration-500">
                        <span class="text-[11px] font-black text-primary uppercase w-full line-clamp-1">{{ $match->awayTeam->name }}</span>
                    </div>
                </div>

                <div class="text-center border-t border-zinc-50 pt-6">
                    <span class="text-[9px] font-black text-primary uppercase tracking-widest group-hover:tracking-[0.2em] transition-all">Relive the Moment →</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

