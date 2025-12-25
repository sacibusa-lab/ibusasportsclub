@extends('layout')

@section('content')
<div class="max-w-[1000px] mx-auto space-y-12">
    <!-- Header/Hero Section -->
    <div class="bg-primary rounded-[2.5rem] p-8 md:p-16 text-center relative overflow-hidden border-4 border-white">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-[#2a1042] to-secondary/20 animate-gradient-xy"></div>
        
        <div class="relative z-10 space-y-6">
            <h1 class="text-4xl md:text-6xl font-black text-white italic tracking-tighter uppercase font-outfit">Predictor League</h1>
            <p class="text-zinc-400 text-xs md:text-sm font-bold uppercase tracking-[0.3em] max-w-xl mx-auto leading-relaxed">Predict scores, earn points, and win legendary prizes this season</p>
            
            <!-- Tiered Prizes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-8">
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 group hover:border-secondary transition">
                    <div class="text-secondary font-black text-2xl mb-1 italic">1ST</div>
                    <div class="text-white font-bold text-sm uppercase tracking-widest">{{ $activeCompetition->predictor_prize_1 ?? 'Ultimate Trophy + Cash' }}</div>
                </div>
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 group hover:border-secondary transition">
                    <div class="text-zinc-300 font-black text-2xl mb-1 italic">2ND</div>
                    <div class="text-white font-bold text-sm uppercase tracking-widest">{{ $activeCompetition->predictor_prize_2 ?? 'Official Jersey' }}</div>
                </div>
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 group hover:border-secondary transition">
                    <div class="text-[#cd7f32] font-black text-2xl mb-1 italic">3RD</div>
                    <div class="text-white font-bold text-sm uppercase tracking-widest">{{ $activeCompetition->predictor_prize_3 ?? 'Tournament Souvenir' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Predictions Column -->
        <div class="lg:col-span-2 space-y-8">
            <h2 class="text-2xl font-black text-primary uppercase italic tracking-tighter">Upcoming Predictions</h2>
            
            @if($upcomingMatches->count() > 0)
            <div class="space-y-4">
                @foreach($upcomingMatches as $match)
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-zinc-100">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">{{ $match->match_date->format('D, j M - H:i') }}</span>
                        <span class="text-[9px] font-black text-primary bg-secondary/20 px-3 py-1 rounded-full uppercase tracking-widest">{{ $match->venue }}</span>
                    </div>

                    <form action="{{ route('predictor.predict') }}" method="POST" class="flex items-center justify-between gap-4 md:gap-8">
                        @csrf
                        <input type="hidden" name="match_id" value="{{ $match->id }}">
                        
                        <!-- Home Team -->
                        <div class="flex-1 text-right flex flex-col items-end gap-2">
                            @if($match->homeTeam->logo_url)
                            <img src="{{ $match->homeTeam->logo_url }}" class="h-10 w-10 object-contain">
                            @endif
                            <span class="font-black text-sm uppercase text-primary leading-tight">{{ $match->homeTeam->name }}</span>
                        </div>

                        <!-- Prediction Inputs -->
                        <div class="flex items-center gap-2">
                            @auth
                            <input type="number" name="home_score" min="0" required
                                class="w-12 h-14 bg-zinc-50 border border-zinc-200 rounded-xl text-center font-black text-xl text-primary focus:ring-2 focus:ring-secondary focus:border-transparent outline-none transition"
                                {{ in_array($match->id, $userPredictions) ? 'placeholder=?' : 'value=0' }}>
                            <span class="text-zinc-300 font-black">-</span>
                            <input type="number" name="away_score" min="0" required
                                class="w-12 h-14 bg-zinc-50 border border-zinc-200 rounded-xl text-center font-black text-xl text-primary focus:ring-2 focus:ring-secondary focus:border-transparent outline-none transition"
                                {{ in_array($match->id, $userPredictions) ? 'placeholder=?' : 'value=0' }}>
                            @else
                            <div class="bg-zinc-100 px-4 py-2 rounded-xl text-[10px] font-black uppercase text-zinc-400">Locked</div>
                            @endauth
                        </div>

                        <!-- Away Team -->
                        <div class="flex-1 text-left flex flex-col items-start gap-2">
                            @if($match->awayTeam->logo_url)
                            <img src="{{ $match->awayTeam->logo_url }}" class="h-10 w-10 object-contain">
                            @endif
                            <span class="font-black text-sm uppercase text-primary leading-tight">{{ $match->awayTeam->name }}</span>
                        </div>

                        <div class="shrink-0">
                            @auth
                            <button type="submit" class="bg-primary text-secondary p-3 rounded-xl hover:bg-secondary hover:text-primary transition group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="text-[9px] font-black text-primary hover:text-secondary uppercase underline tracking-widest">Login to Predict</a>
                            @endauth
                        </div>
                    </form>
                    
                    @if(in_array($match->id, $userPredictions))
                    <div class="mt-4 pt-4 border-t border-dashed border-zinc-100 text-center">
                        <span class="text-[9px] font-black text-secondary uppercase tracking-[0.2em] italic">Prediction Submitted Successfully</span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-zinc-100">
                <span class="text-zinc-300 font-black uppercase tracking-widest text-[10px]">No upcoming matches for prediction.</span>
            </div>
            @endif
        </div>

        <!-- Leaderboard Column -->
        <div class="space-y-8">
            <h2 class="text-2xl font-black text-primary uppercase italic tracking-tighter">Leaderboard</h2>
            <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-zinc-100 bg-zinc-50/50">
                    <span class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em]">Top Tipsters</span>
                </div>
                <div class="divide-y divide-zinc-50">
                    @forelse($leaderboard as $index => $user)
                    <div class="p-5 flex items-center justify-between hover:bg-zinc-50 transition group">
                        <div class="flex items-center gap-4">
                            <span class="w-6 font-black italic {{ $index < 3 ? 'text-secondary' : 'text-zinc-300' }}">{{ $index + 1 }}</span>
                            <div class="flex flex-col">
                                <span class="font-bold text-sm text-primary uppercase leading-none">{{ $user->name }}</span>
                                <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest mt-1 group-hover:text-secondary transition">Ranked Tipster</span>
                            </div>
                        </div>
                        <div class="bg-zinc-100 px-3 py-1 rounded-full group-hover:bg-primary group-hover:text-white transition">
                            <span class="text-xs font-black">{{ $user->predictor_points }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <span class="text-zinc-300 font-black uppercase tracking-widest text-[9px]">League starting soon</span>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Rules Card -->
            <div class="bg-secondary rounded-[2rem] p-8 space-y-4 shadow-xl">
                <h3 class="text-primary font-black uppercase italic tracking-tighter text-xl">Scoring Guide</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider">
                        <span class="text-primary/60">Correct Score</span>
                        <span class="bg-primary text-white px-2 py-0.5 rounded italic">5 PTS</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider">
                        <span class="text-primary/60">Correct Result</span>
                        <span class="bg-primary text-white px-2 py-0.5 rounded italic">2 PTS</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider border-t border-primary/10 pt-3">
                        <span class="text-primary/60">Incorrect</span>
                        <span class="bg-zinc-200 text-zinc-400 px-2 py-0.5 rounded italic">0 PTS</span>
                    </div>
                </div>
            </div>

            <!-- My Predictions Section -->
            @auth
            <div class="bg-white rounded-[2rem] border border-zinc-100 shadow-sm overflow-hidden mt-8">
                <div class="p-6 border-b border-zinc-100 bg-zinc-50/50">
                    <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">My Predictions</span>
                </div>
                <div class="divide-y divide-zinc-50 max-h-[400px] overflow-y-auto no-scrollbar">
                    @forelse($myPredictions as $p)
                    <div class="p-4 space-y-2 group">
                        <div class="flex items-center justify-between">
                            <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">{{ $p->match->match_date->format('j M') }}</span>
                            @if($p->is_processed)
                            <span class="text-[8px] font-black {{ $p->points_earned > 0 ? 'text-emerald-500' : 'text-zinc-300' }} uppercase tracking-widest">+{{ $p->points_earned }} PTS</span>
                            @else
                            <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest italic">Pending</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-bold text-primary truncate max-w-[60px]">{{ $p->match->homeTeam->name }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="bg-zinc-100 text-primary px-2 py-0.5 rounded-md font-black text-[11px]">{{ $p->home_score }}</span>
                                <span class="text-zinc-300 text-[9px] font-black">-</span>
                                <span class="bg-zinc-100 text-primary px-2 py-0.5 rounded-md font-black text-[11px]">{{ $p->away_score }}</span>
                            </div>
                            <span class="text-[10px] font-bold text-primary truncate max-w-[60px] text-right">{{ $p->match->awayTeam->name }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <span class="text-zinc-300 font-black uppercase tracking-widest text-[8px]">No predictions yet</span>
                    </div>
                    @endforelse
                </div>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
