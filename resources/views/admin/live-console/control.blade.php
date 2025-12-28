@extends('admin.layout')

@section('title', 'Live Console Control')

@push('styles')
<style>
    .stat-btn {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-btn:active {
        transform: scale(0.95);
    }
    .home-theme { --team-color: #3b82f6; }
    .away-theme { --team-color: #ef4444; }
    
    .btn-stat-box {
        background: var(--team-color);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        border-radius: 2rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border: 2px solid rgba(255,255,255,0.1);
    }
    .btn-stat-box:hover {
        filter: brightness(1.1);
        transform: translateY(-4px);
        box-shadow: 0 25px 30px -10px rgba(0, 0, 0, 0.2);
    }
    .count-badge {
        font-weight: 900;
        font-size: 2.5rem;
        line-height: 1;
        margin-top: 0.5rem;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="max-w-[1800px] mx-auto space-y-12 pb-20">
    <!-- Header / Scoreboard -->
    <div class="bg-white rounded-[3.5rem] p-10 shadow-xl border border-zinc-100 flex items-center justify-between relative overflow-hidden">
        <!-- Home Team Header -->
        <div class="flex items-center gap-8 flex-1">
            @if($match->homeTeam->logo_url)
            <img src="{{ $match->homeTeam->logo_url }}" class="w-24 h-24 object-contain">
            @endif
            <div>
                <h2 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none mb-2">{{ $match->homeTeam->name }}</h2>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest bg-zinc-50 px-3 py-1 rounded-full">Home Team</span>
            </div>
        </div>

        <!-- Center Scoreboard -->
        <div class="flex flex-col items-center gap-4 px-16">
            <div id="match-timer" class="text-6xl font-black text-rose-500 tabular-nums tracking-tighter mb-2">
                00:00
            </div>
            
            <div class="flex flex-col items-center gap-4">
                @if(!$match->started_at)
                <button id="start-match-btn" onclick="startMatch()" class="bg-rose-500 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-rose-600 transition-all hover:scale-105 active:scale-95">
                    START MATCH
                </button>
                @endif
                
                @if($match->started_at && $match->status !== 'finished')
                <div class="flex gap-2">
                    <button id="pause-match-btn" onclick="togglePause()" class="{{ $match->is_paused ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-amber-500 hover:bg-amber-600' }} text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg transition-all hover:scale-105 active:scale-95">
                        {{ $match->is_paused ? 'RESUME' : 'PAUSE' }}
                    </button>
                    <button id="end-match-btn" onclick="endMatch()" class="bg-zinc-900 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-zinc-800 transition-all hover:scale-105 active:scale-95">
                        END
                    </button>
                </div>
                @endif

                <select onchange="updateMatchStatus(this.value)" class="bg-zinc-50 border border-zinc-100 px-4 py-2 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-[10px] tracking-widest cursor-pointer">
                    <option value="upcoming" {{ $match->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="live" {{ $match->status == 'live' ? 'selected' : '' }}>Live</option>
                    <option value="finished" {{ $match->status == 'finished' ? 'selected' : '' }}>Finished</option>
                </select>
            </div>

            <div class="flex items-center gap-12 mt-4">
                <span id="home-score" class="text-[10rem] font-black text-zinc-900 leading-none drop-shadow-sm">{{ $match->home_score ?? 0 }}</span>
                <span class="text-5xl text-zinc-200 font-thin">-</span>
                <span id="away-score" class="text-[10rem] font-black text-zinc-900 leading-none drop-shadow-sm">{{ $match->away_score ?? 0 }}</span>
            </div>

            <div class="flex flex-col items-center gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-rose-500/20 rounded-full flex items-center justify-center">
                        <div class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></div>
                    </div>
                    <span id="live-indicator-text" class="text-[11px] font-black text-zinc-300 uppercase tracking-[0.5em]">{{ $match->status == 'live' ? 'Live Match' : 'Live Console' }}</span>
                </div>
            </div>
        </div>

        <!-- Away Team Header -->
        <div class="flex items-center gap-8 flex-1 justify-end text-right">
            <div>
                <h2 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none mb-2">{{ $match->awayTeam->name }}</h2>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest bg-zinc-50 px-3 py-1 rounded-full">Away Team</span>
            </div>
            @if($match->awayTeam->logo_url)
            <img src="{{ $match->awayTeam->logo_url }}" class="w-24 h-24 object-contain">
            @endif
        </div>
    </div>

    <!-- Interface Grid: 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        <!-- COLUMN 1: HOME TEAM -->
        <div class="home-theme space-y-8 animate-slide-in">
            <div class="bg-blue-600 text-white py-10 rounded-[2.5rem] text-center font-black text-4xl tracking-widest uppercase shadow-2xl border-4 border-white/20">
                HOME
            </div>

            <!-- Stat Buttons Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $stats = [
                        ['id' => 'throw_ins', 'label' => 'Throw In'],
                        ['id' => 'free_kicks', 'label' => 'Free Kick'],
                        ['id' => 'fouls', 'label' => 'Foul'],
                        ['id' => 'corners', 'label' => 'Corner'],
                        ['id' => 'goal_kicks', 'label' => 'Goal Kick'],
                        ['id' => 'offsides', 'label' => 'Offside'],
                        ['id' => 'shots', 'label' => 'Shot On Target'],
                        ['id' => 'saves', 'label' => 'Save'],
                        ['id' => 'missed_chances', 'label' => 'Missed Chances'],
                    ];
                @endphp

                @foreach($stats as $stat)
                <button onclick="updateStat('home', '{{ $stat['id'] }}')" class="stat-btn btn-stat-box">
                    <span class="text-[10px] opacity-80 mb-2">{{ $stat['label'] }}</span>
                    <div id="home-{{ $stat['id'] }}-count" class="count-badge text-3xl leading-none">{{ $match->{'home_'.$stat['id']} }}</div>
                </button>
                @endforeach
            </div>

            <!-- Player List -->
            <div class="bg-white rounded-[3rem] p-8 shadow-sm border border-zinc-100">
                <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.25em] border-b border-blue-50 pb-5 mb-6 flex items-center justify-between">
                    <span>Starting XI</span>
                    <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                </h3>
                <div class="space-y-3">
                    @foreach($homeXI as $player)
                    <div class="group bg-zinc-50 hover:bg-white p-4 rounded-2xl border border-transparent hover:border-blue-100 flex items-center justify-between transition-all hover:shadow-lg">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center text-xs font-black shadow-sm">{{ $player->pivot->shirt_number }}</span>
                            <span class="text-sm font-black text-primary uppercase tracking-tight">{{ $player->name }}</span>
                        </div>
                        <div class="flex gap-2 flex-wrap justify-end">
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'goal')" class="px-3 py-2 bg-blue-600 text-white text-[10px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 shadow-md flex items-center gap-1">
                                <span>Goal</span>
                            </button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'penalty_goal')" class="px-3 py-2 bg-emerald-500 text-white text-[10px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 shadow-md flex items-center gap-1">
                                <span>P.Goal</span>
                            </button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'penalty_missed')" class="px-3 py-2 bg-zinc-400 text-white text-[10px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 shadow-md flex items-center gap-1">
                                <span>P.Miss</span>
                            </button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'yellow_card')" class="px-3 py-2 bg-amber-400 text-white font-black text-[10px] rounded-lg hover:scale-105 active:scale-95 shadow-md flex items-center gap-1 uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                                <span>Yel</span>
                            </button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'red_card')" class="px-3 py-2 bg-rose-600 text-white font-black text-[10px] rounded-lg hover:scale-105 active:scale-95 shadow-md flex items-center gap-1 uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                                <span>Red</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- COLUMN 2: AWAY TEAM -->
        <div class="away-theme space-y-8 animate-slide-in" style="animation-delay: 0.1s">
            <div class="bg-rose-600 text-white py-10 rounded-[2.5rem] text-center font-black text-4xl tracking-widest uppercase shadow-2xl border-4 border-white/20">
                AWAY
            </div>

            <!-- Stat Buttons Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($stats as $stat)
                <button onclick="updateStat('away', '{{ $stat['id'] }}')" class="stat-btn btn-stat-box">
                    <span class="text-[10px] opacity-80 mb-2">{{ $stat['label'] }}</span>
                    <div id="away-{{ $stat['id'] }}-count" class="count-badge text-3xl leading-none">{{ $match->{'away_'.$stat['id']} }}</div>
                </button>
                @endforeach
            </div>

            <!-- Player List -->
            <div class="bg-white rounded-[3rem] p-8 shadow-sm border border-zinc-100">
                <h3 class="text-xs font-black text-rose-600 uppercase tracking-[0.25em] border-b border-rose-50 pb-5 mb-6 flex items-center justify-between">
                    <span>Starting XI</span>
                    <span class="w-2 h-2 bg-rose-600 rounded-full mr-2"></span>
                </h3>
                <div class="space-y-3">
                    @foreach($awayXI as $player)
                    <div class="group bg-zinc-50 hover:bg-white p-4 rounded-2xl border border-transparent hover:border-red-100 flex items-center justify-between transition-all hover:shadow-lg">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 bg-rose-600 text-white rounded-lg flex items-center justify-center text-xs font-black shadow-sm">{{ $player->pivot->shirt_number }}</span>
                            <span class="text-sm font-black text-primary uppercase tracking-tight">{{ $player->name }}</span>
                        </div>
                        <div class="flex gap-2 flex-wrap justify-end">
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'goal')" class="px-3 py-2 bg-rose-600 text-white text-[10px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 shadow-md flex items-center gap-1">
                                <span>Goal</span>
                            </button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'penalty_goal')" class="px-3 py-2 bg-emerald-500 text-white text-[10px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 shadow-md flex items-center gap-1">
                                <span>P.Goal</span>
                            </button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'penalty_missed')" class="px-3 py-2 bg-zinc-400 text-white text-[10px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 shadow-md flex items-center gap-1">
                                <span>P.Miss</span>
                            </button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'yellow_card')" class="px-3 py-2 bg-amber-400 text-white font-black text-[10px] rounded-lg hover:scale-105 active:scale-95 shadow-md flex items-center gap-1 uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                                <span>Yel</span>
                            </button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'red_card')" class="px-3 py-2 bg-rose-600 text-white font-black text-[10px] rounded-lg hover:scale-105 active:scale-95 shadow-md flex items-center gap-1 uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                                <span>Red</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- TIMELINE: FULL WIDTH AT BOTTOM -->
    <div class="animate-slide-in" style="animation-delay: 0.2s">
        <div class="bg-white rounded-[3.5rem] p-10 shadow-xl border border-zinc-100 flex flex-col">
            <div class="flex items-center justify-between mb-10 border-b border-zinc-50 pb-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-primary text-secondary rounded-[1.5rem] flex items-center justify-center font-black shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-primary uppercase tracking-tight">Match Timeline</h3>
                        <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest mt-1">Live Event Feed</p>
                    </div>
                </div>
                <div class="bg-emerald-50 px-6 py-2 rounded-full flex items-center gap-3">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-xs font-black text-emerald-600 uppercase tracking-widest">Active Stream</span>
                </div>
            </div>

            <div id="event-feed" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($match->matchEvents->sortByDesc('created_at') as $event)
                <div class="flex items-start gap-4 p-5 rounded-[2rem] bg-zinc-50 border border-zinc-100 group hover:border-primary/20 hover:bg-white transition-all hover:shadow-xl animate-slide-in">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-zinc-100 flex items-center justify-center font-black text-primary italic text-lg group-hover:scale-110 transition-transform shrink-0">
                        {{ $event->minute }}'
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] {{ $event->team_id == $match->home_team_id ? 'text-blue-600' : 'text-red-600' }}">
                                {{ $event->team_id == $match->home_team_id ? $match->homeTeam->name : $match->awayTeam->name }}
                            </span>
                        </div>
                        <div class="text-[15px] font-black text-primary uppercase tracking-tight leading-tight">
                            @if($event->event_type === 'goal')
                                <span class="text-emerald-500">GOAL! ⚽</span> 
                            @elseif($event->event_type === 'penalty_goal')
                                <span class="text-emerald-500">PENALTY GOAL! ⚽🎯</span>
                            @elseif($event->event_type === 'penalty_missed')
                                <span class="text-rose-500">PENALTY MISSED ❌🧤</span>
                            @endif
                            {{ str_replace('_', ' ', $event->event_type) }} 
                            @if($event->player_name)
                                <div class="text-xs text-zinc-400 font-bold mt-1 tracking-normal">
                                    {{ $event->player_name }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-[10px] font-black text-zinc-300 uppercase italic">
                        {{ $event->created_at->format('H:i:s') }}
                    </div>
                </div>
                @endforeach

                @if($match->matchEvents->isEmpty())
                <div id="no-events-msg" class="col-span-full text-center py-20 bg-zinc-50/50 rounded-[3rem] border-2 border-dashed border-zinc-100">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                         <svg class="w-10 h-10 text-zinc-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[11px] font-black text-zinc-300 uppercase tracking-[0.4em]">Awaiting Kickoff & First Events</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let matchStartedAt = @json($match->started_at ? $match->started_at->toIso8601String() : null);
    let isPaused = @json($match->is_paused);
    let totalPausedSeconds = {{ $match->total_paused_seconds ?? 0 }};
    let pausedAt = @json($match->paused_at ? $match->paused_at->toIso8601String() : null);
    let timerInterval;

    function updateTimer() {
        if (!matchStartedAt) return;
        
        const start = new Date(matchStartedAt);
        const now = isPaused ? new Date(pausedAt) : new Date();
        const diffInSeconds = Math.floor((now - start) / 1000) - totalPausedSeconds;
        
        if (diffInSeconds < 0) {
            document.getElementById('match-timer').textContent = "00:00";
            return;
        }

        const mins = Math.floor(diffInSeconds / 60);
        const secs = diffInSeconds % 60;
        
        document.getElementById('match-timer').textContent = 
            `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    if (matchStartedAt) {
        timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    }

    function togglePause() {
        fetch(`{{ route('admin.matches.toggle-pause', $match->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                isPaused = data.is_paused;
                totalPausedSeconds = data.total_paused_seconds;
                pausedAt = data.paused_at;
                
                const btn = document.getElementById('pause-match-btn');
                if (isPaused) {
                    btn.textContent = 'RESUME';
                    btn.classList.remove('bg-amber-500', 'hover:bg-amber-600');
                    btn.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
                } else {
                    btn.textContent = 'PAUSE';
                    btn.classList.remove('bg-emerald-500', 'hover:bg-emerald-600');
                    btn.classList.add('bg-amber-500', 'hover:bg-amber-600');
                }
                updateTimer();
            }
        });
    }

    function startMatch() {
        fetch(`{{ route('admin.matches.start-timer', $match->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                matchStartedAt = data.started_at;
                isPaused = false;
                totalPausedSeconds = 0;
                location.reload();
            }
        });
    }

    function endMatch() {
        if (!confirm("Are you sure you want to end this match? This will finalize the score and calculate prediction points.")) return;
        
        fetch(`{{ route('admin.matches.end-match', $match->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function getMatchMinute() {
        if (!matchStartedAt) return 0;
        const start = new Date(matchStartedAt);
        const now = isPaused ? new Date(pausedAt) : new Date();
        const diffInSeconds = Math.floor((now - start) / 1000) - totalPausedSeconds;
        return Math.floor(diffInSeconds / 60) + 1; // Current minute
    }

    function updateMatchStatus(status) {
        fetch(`{{ route('admin.fixtures.update', $match->id) }}`, {
            method: 'PUT',
            body: JSON.stringify({ status, stage: '{{ $match->stage }}' }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function appendEventToFeed(event) {
        if (!event) return;
        
        const feed = document.getElementById('event-feed');
        const noEventsMsg = document.getElementById('no-events-msg');
        if (noEventsMsg) noEventsMsg.remove();

        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const teamName = event.team_id == {{ $match->home_team_id }} ? '{{ $match->homeTeam->name }}' : '{{ $match->awayTeam->name }}';
        const teamClass = event.team_id == {{ $match->home_team_id }} ? 'text-blue-600' : 'text-red-600';

        const eventHtml = `
            <div class="flex items-start gap-4 p-5 rounded-[2rem] bg-zinc-50 border border-zinc-100 group hover:border-primary/20 hover:bg-white transition-all hover:shadow-xl animate-slide-in">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-zinc-100 flex items-center justify-center font-black text-primary italic text-lg group-hover:scale-110 transition-transform shrink-0">
                    ${event.minute}'
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] ${teamClass}">
                            ${teamName}
                        </span>
                    </div>
                    <div class="text-[15px] font-black text-primary uppercase tracking-tight leading-tight">
                        ${event.event_type === 'goal' ? '<span class="text-emerald-500">GOAL! ⚽</span> ' : ''}
                        ${event.event_type === 'penalty_goal' ? '<span class="text-emerald-500">PENALTY GOAL! ⚽🎯</span> ' : ''}
                        ${event.event_type === 'penalty_missed' ? '<span class="text-rose-500">PENALTY MISSED ❌🧤</span> ' : ''}
                        ${event.event_type.replace('_', ' ')} 
                        ${event.player_name ? `<div class="text-xs text-zinc-400 font-bold mt-1 tracking-normal">${event.player_name}</div>` : ''}
                    </div>
                </div>
                <div class="text-[10px] font-black text-zinc-300 uppercase italic">
                    ${time}
                </div>
            </div>
        `;
        
        feed.insertAdjacentHTML('afterbegin', eventHtml);
    }

    function updateStat(side, stat) {
        fetch(`{{ route('admin.matches.quick-stat', $match->id) }}`, {
            method: 'POST',
            body: JSON.stringify({ side, stat }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update specific stat count
                document.getElementById(`${side}-${stat}-count`).textContent = data.new_value;
                
                // Sync Scoreboard (Resilient)
                document.getElementById('home-score').textContent = data.home_score;
                document.getElementById('away-score').textContent = data.away_score;

                // Sync Feed
                appendEventToFeed(data.event);

                // If match was auto-started (started_at came back but wasn't here), reload to show timer
                if (!matchStartedAt && data.event) {
                    location.reload();
                }

                // If status was updated to live automatically, ensure UI reflects it
                if (data.status === 'live') {
                    const statusSelect = document.querySelector('select[onchange="updateMatchStatus(this.value)"]');
                    if (statusSelect) statusSelect.value = 'live';
                    document.getElementById('live-indicator-text').textContent = 'Live Match';
                }

                // Add mini pop animation
                const badge = document.getElementById(`${side}-${stat}-count`);
                badge.classList.add('animate-bounce');
                setTimeout(() => badge.classList.remove('animate-bounce'), 500);
            }
        });
    }

    function logPlayerEvent(teamId, playerId, type) {
        let min = getMatchMinute();
        
        if (!matchStartedAt) {
            min = prompt("Match not started. Enter minute manually:", "0");
            if (min === null) return;
        }

        fetch(`{{ route('admin.matches.quick-event', $match->id) }}`, {
            method: 'POST',
            body: JSON.stringify({ 
                team_id: teamId, 
                player_id: playerId, 
                event_type: type,
                minute: min 
            }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Sync Scoreboard
                document.getElementById('home-score').textContent = data.home_score;
                document.getElementById('away-score').textContent = data.away_score;
                
                // Add pop animation to score
                if (type === 'goal' || type === 'penalty_goal') {
                    const scoreId = teamId == {{ $match->home_team_id }} ? 'home-score' : 'away-score';
                    const el = document.getElementById(scoreId);
                    el.classList.add('animate-bounce', 'text-rose-600');
                    setTimeout(() => el.classList.remove('animate-bounce', 'text-rose-600'), 1000);
                }

                // Sync Feed
                appendEventToFeed(data.event);

                // If status was updated to live automatically, ensure UI reflects it
                if (data.status === 'live') {
                    const statusSelect = document.querySelector('select[onchange="updateMatchStatus(this.value)"]');
                    if (statusSelect) statusSelect.value = 'live';
                    document.getElementById('live-indicator-text').textContent = 'Live Match';
                }

                // If match was auto-started, refresh to show timer and end button
                if (!matchStartedAt && data.event && data.event.minute) {
                   location.reload();
                }
                
                // Toast success
                const toast = document.createElement('div');
                toast.className = "fixed bottom-8 right-8 bg-zinc-900 text-emerald-400 px-8 py-4 rounded-3xl font-black text-xs uppercase tracking-widest shadow-2xl z-[100] animate-bounce";
                toast.textContent = `${type.replace('_', ' ')} recorded!`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        });
    }
</script>
@endpush
@endsection
