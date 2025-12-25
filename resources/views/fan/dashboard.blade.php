@extends('layout')

@section('title', 'Predictor Dashboard | ' . $siteSettings['site_name'])

@section('content')
<style>
    .tier-bronze { --tier-color: #cd7f32; --tier-bg: rgba(205, 127, 50, 0.1); }
    .tier-silver { --tier-color: #c0c0c0; --tier-bg: rgba(192, 192, 192, 0.1); }
    .tier-gold { --tier-color: #ffd700; --tier-bg: rgba(255, 215, 0, 0.1); }
    .tier-platinum { --tier-color: #e5e4e2; --tier-bg: rgba(229, 228, 226, 0.1); }

    .glow-card {
        box-shadow: 0 0 20px -5px var(--tier-color);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .rank-text { color: var(--tier-color); }
    
    @keyframes pulse-ring {
        0% { transform: scale(.33); opacity: 0; }
        80%, 100% { opacity: 0; }
    }
</style>

<div class="space-y-12 pb-20 tier-{{ strtolower($rankTier) }}">
    <!-- Header/Welcome -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-primary uppercase tracking-tighter italic">Career Hub</h1>
            <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest mt-1">Welcome back, {{ explode(' ', $user->name)[0] }}</p>
        </div>
        <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-2xl shadow-sm border border-zinc-100">
            <div class="w-12 h-12 bg-{{ strtolower($rankTier) == 'bronze' ? 'amber-700' : (strtolower($rankTier) == 'silver' ? 'zinc-400' : (strtolower($rankTier) == 'gold' ? 'yellow-400' : 'slate-300')) }} rounded-xl flex items-center justify-center text-white text-xl shadow-lg">
                {{ substr($rankTier, 0, 1) }}
            </div>
            <div>
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block">Current Rank</span>
                <span class="text-sm font-black text-primary uppercase rank-text">{{ $rankTier }} Tier</span>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid lg:grid-cols-12 gap-8">
        <!-- Left: Fan Card & Stats -->
        <div class="lg:col-span-4 space-y-8">
            <!-- The Evolving Fan Card -->
            <div class="glow-card bg-neutral-900 rounded-[2.5rem] p-8 relative overflow-hidden group aspect-[1.6/1] flex flex-col justify-between">
                <!-- Rank Gradient Overlay -->
                <div class="absolute inset-0 opacity-20" style="background: radial-gradient(circle at 70% 30%, var(--tier-color), transparent 70%)"></div>
                
                <div class="relative z-10 flex justify-between items-start">
                    <div class="w-12 h-14 bg-primary flex items-center justify-center rounded-lg border border-white/10 shadow-lg">
                        <span class="text-secondary font-black text-xs italic">{{ $siteSettings['site_short_name'] }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[8px] font-black uppercase tracking-[0.3em] rank-text">{{ $rankTier }} Member</span>
                        <div class="text-[10px] font-black text-white uppercase tracking-widest mt-0.5">Predictor Hub</div>
                    </div>
                </div>

                <div class="relative z-10 space-y-4">
                    <div class="space-y-0.5">
                        <span class="text-[8px] font-black text-zinc-500 uppercase tracking-widest">Active Player</span>
                        <h3 class="text-2xl font-black text-white uppercase tracking-tighter truncate">{{ $user->name }}</h3>
                    </div>
                    
                    <div class="flex items-end justify-between">
                        <div class="space-y-0.5">
                            <span class="text-[8px] font-black text-zinc-500 uppercase tracking-widest">Career Points</span>
                            <p class="text-lg font-black tracking-widest rank-text">{{ number_format($user->predictor_points) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center border border-white/10">
                             <span class="text-2xl">⚽</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Mini-Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-3xl border border-zinc-100 shadow-sm group hover:border-secondary transition">
                    <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest block mb-1">Sniper Rating</span>
                    <div class="flex items-end gap-1">
                        <span class="text-2xl font-black text-primary italic">{{ $stats['exact'] }}</span>
                        <span class="text-[10px] font-bold text-zinc-400 mb-1">Exacts</span>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-3xl border border-zinc-100 shadow-sm group hover:border-secondary transition">
                    <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block mb-1">Hit Rate</span>
                    <div class="flex items-end gap-1">
                        <span class="text-2xl font-black text-primary italic">{{ $stats['accuracy'] }}%</span>
                        <span class="text-[10px] font-bold text-zinc-400 mb-1">Accuracy</span>
                    </div>
                </div>
            </div>

            <!-- New: Performance Breakdown -->
            <div class="bg-white p-6 rounded-[2.5rem] border border-zinc-100 shadow-sm space-y-6">
                <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em]">Accuracy Breakdown</h3>
                <div class="space-y-4">
                    @foreach(['home' => 'Home Win', 'away' => 'Away Win', 'draw' => 'Draw'] as $key => $label)
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-[10px] font-black uppercase tracking-widest">
                            <span class="text-zinc-500">{{ $label }}</span>
                            <span class="text-primary">{{ $granularStats[$key]['percentage'] }}%</span>
                        </div>
                        <div class="h-1.5 w-full bg-zinc-50 rounded-full overflow-hidden">
                            <div class="h-full bg-secondary rounded-full transition-all duration-1000" style="width: {{ $granularStats[$key]['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider italic">Based on {{ $stats['total'] }} processed predictions</p>
            </div>
        </div>

        <!-- Right: Badges & History -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Badge Wall -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-zinc-100 shadow-sm relative overflow-hidden">
                <h2 class="text-lg font-black text-primary uppercase tracking-tighter italic mb-8 flex items-center gap-2">
                    <span class="w-2 h-6 bg-secondary rounded-full"></span>
                    Achievement Wall
                </h2>
                
                @if(count($badges) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                    @foreach($badges as $badge)
                    <div class="flex flex-col items-center text-center group cursor-help">
                        <div class="w-16 h-16 bg-zinc-50 rounded-2xl flex items-center justify-center text-3xl shadow-inner group-hover:bg-white group-hover:shadow-xl transition duration-500 relative">
                            {{ $badge['icon'] }}
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-secondary rounded-full border-2 border-white flex items-center justify-center">
                                <svg class="w-2 h-2 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest mt-4">{{ $badge['name'] }}</span>
                        <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-wider mt-0.5 opacity-0 group-hover:opacity-100 transition">{{ $badge['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 opacity-30">
                    <span class="text-4xl block mb-2">🔒</span>
                    <p class="text-[10px] font-black uppercase tracking-widest">Make predictions to start unlocking badges</p>
                </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-zinc-100 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-lg font-black text-primary uppercase tracking-tighter italic flex items-center gap-2">
                        <span class="w-2 h-6 bg-primary rounded-full"></span>
                        Recent Predictions
                    </h2>
                    <a href="{{ route('predictor.index') }}" class="text-[9px] font-black text-secondary hover:underline uppercase tracking-widest">Make More +</a>
                </div>

                <div class="space-y-4">
                    @forelse($recentPredictions as $pred)
                    <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-zinc-50 transition border border-transparent hover:border-zinc-100 group">
                        <div class="flex items-center gap-4">
                            <div class="flex -space-x-1">
                                <img src="{{ $pred->match->homeTeam->logo_url }}" class="w-8 h-8 object-contain">
                                <img src="{{ $pred->match->awayTeam->logo_url }}" class="w-8 h-8 object-contain">
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-primary uppercase line-clamp-1">{{ $pred->match->homeTeam->name }} vs {{ $pred->match->awayTeam->name }}</p>
                                <span class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest">Predicted: {{ $pred->home_score }} - {{ $pred->away_score }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($pred->is_processed)
                            <div class="flex flex-col items-end">
                                <span class="text-[11px] font-black {{ $pred->points_earned > 0 ? 'text-green-500' : 'text-rose-500' }}">
                                    {{ $pred->points_earned > 0 ? '+' . $pred->points_earned : '0' }} PTS
                                </span>
                                <span class="text-[8px] font-bold text-zinc-400 uppercase">{{ $pred->points_earned == 5 ? 'EXACT SCORE' : ($pred->points_earned == 2 ? 'CORRECT RESULT' : 'NO POINTS') }}</span>
                            </div>
                            @else
                            <span class="text-[9px] font-black text-zinc-300 uppercase italic tracking-widest">PENDING</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="py-12 text-center">
                         <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest italic">Your prediction history is empty</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
