@extends('layout')

@section('content')
<div class="max-w-[800px] mx-auto space-y-12">
    <h2 class="text-3xl font-black text-primary flex items-center justify-between uppercase tracking-tighter italic">
        Results
    </h2>

    <div class="space-y-4">
        @foreach($results as $match)
        <a href="{{ route('match.details', $match->id) }}" class="bg-white rounded-xl p-6 flex items-center justify-between shadow-sm border border-zinc-100 hover:border-primary transition-all group">
            <div class="flex-1 flex items-center justify-end gap-3 font-black text-primary text-sm md:text-base group-hover:pr-2 transition-all">
                {{ $match->homeTeam->name }}
                @if($match->homeTeam->logo_url)
                <img src="{{ $match->homeTeam->logo_url }}" class="w-8 h-8 object-contain">
                @endif
            </div>
            
            <div class="flex items-center gap-1 mx-8 shrink-0">
                <div class="w-10 h-10 bg-primary text-white rounded flex items-center justify-center text-xl font-black shadow-lg">{{ $match->home_score }}</div>
                <div class="w-10 h-10 bg-primary text-white rounded flex items-center justify-center text-xl font-black shadow-lg">{{ $match->away_score }}</div>
            </div>

            <div class="flex-1 flex items-center justify-start gap-3 font-black text-primary text-sm md:text-base group-hover:pl-2 transition-all">
                @if($match->awayTeam->logo_url)
                <img src="{{ $match->awayTeam->logo_url }}" class="w-8 h-8 object-contain">
                @endif
                {{ $match->awayTeam->name }}
            </div>
        </a>
        @endforeach
    </div>

    @if($noveltyResults->count() > 0)
    <div class="space-y-8 pt-12 border-t border-zinc-100">
        <div class="text-center space-y-2">
            <h3 class="text-2xl font-black text-accent uppercase italic tracking-tighter">Special Novelty Matches</h3>
            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em]">One-off Exhibition Results</p>
        </div>

        <div class="grid gap-6">
            @foreach($noveltyResults as $match)
            <a href="{{ route('match.details', $match->id) }}" class="relative overflow-hidden bg-white border-2 border-secondary/20 rounded-[2rem] p-8 hover:border-secondary transition-all group">
                <!-- Decorative Gradient Blur -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-secondary/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-accent/10 rounded-full blur-3xl"></div>

                <div class="absolute top-0 right-0 p-4">
                    <span class="text-[9px] font-black text-primary bg-secondary px-3 py-1 rounded-full uppercase tracking-widest shadow-lg shadow-secondary/20">Novelty Match</span>
                </div>
                
                <div class="flex flex-col md:flex-row items-center justify-between gap-12 relative z-10">
                    <div class="flex flex-col items-center gap-4 flex-1">
                        @if($match->homeTeam->logo_url)
                        <img src="{{ $match->homeTeam->logo_url }}" class="w-20 h-20 object-contain group-hover:scale-110 transition duration-500">
                        @else
                        <div class="w-16 h-16 bg-zinc-100 rounded-full flex items-center justify-center font-black text-xl text-zinc-400">
                            {{ substr($match->homeTeam->name, 0, 1) }}
                        </div>
                        @endif
                        <span class="text-xl font-black text-primary uppercase tracking-tighter">{{ $match->homeTeam->name }}</span>
                    </div>

                    <div class="flex flex-col items-center gap-2">
                        <div class="flex items-center gap-4">
                            <span class="text-6xl font-black text-primary italic">{{ $match->home_score }}</span>
                            <span class="text-2xl font-black text-zinc-200">-</span>
                            <span class="text-6xl font-black text-primary italic">{{ $match->away_score }}</span>
                        </div>
                        <span class="text-[11px] font-black text-zinc-300 uppercase tracking-[0.3em]">{{ \Carbon\Carbon::parse($match->match_date)->format('j F Y') }}</span>
                    </div>

                    <div class="flex flex-col items-center gap-4 flex-1">
                        @if($match->awayTeam->logo_url)
                        <img src="{{ $match->awayTeam->logo_url }}" class="w-20 h-20 object-contain group-hover:scale-110 transition duration-500">
                        @else
                        <div class="w-16 h-16 bg-zinc-100 rounded-full flex items-center justify-center font-black text-xl text-zinc-400">
                            {{ substr($match->awayTeam->name, 0, 1) }}
                        </div>
                        @endif
                        <span class="text-xl font-black text-primary uppercase tracking-tighter">{{ $match->awayTeam->name }}</span>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <span class="text-[10px] font-black text-secondary group-hover:tracking-[0.2em] transition-all uppercase tracking-widest bg-secondary/10 px-6 py-2 rounded-full border border-secondary/20">View Exhibition Match Story →</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($results->count() == 0 && $noveltyResults->count() == 0)
    <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-zinc-100">
        <span class="text-zinc-300 font-black uppercase tracking-widest text-xs">No results recorded yet.</span>
    </div>
    @endif
</div>
@endsection
