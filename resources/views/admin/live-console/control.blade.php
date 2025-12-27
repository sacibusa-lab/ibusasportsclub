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
        padding: 1.25rem 0.75rem;
        border-radius: 1.5rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 2px solid rgba(255,255,255,0.1);
    }
    .btn-stat-box:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .count-badge {
        font-weight: 900;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 0.3s ease-out forwards;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="max-w-[1600px] mx-auto space-y-12">
    <!-- Header / Scoreboard -->
    <div class="bg-white rounded-[3rem] p-8 shadow-xl border border-zinc-100 flex items-center justify-between">
        <div class="flex items-center gap-6 flex-1">
            @if($match->homeTeam->logo_url)
            <img src="{{ $match->homeTeam->logo_url }}" class="w-16 h-16 object-contain">
            @endif
            <div>
                <h2 class="text-2xl font-black text-primary tracking-tighter uppercase leading-none mb-1">{{ $match->homeTeam->name }}</h2>
                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Home Team</span>
            </div>
        </div>

        <div class="flex flex-col items-center gap-4 px-12 border-x border-zinc-100">
            <div id="match-timer" class="text-5xl font-black text-rose-500 tabular-nums tracking-tighter">
                00:00
            </div>
            
            <div class="flex items-center gap-8 py-2">
                <span id="home-score" class="text-8xl font-black text-zinc-900 leading-none">{{ $match->home_score ?? 0 }}</span>
                <span class="text-5xl text-zinc-200 font-thin">-</span>
                <span id="away-score" class="text-8xl font-black text-zinc-900 leading-none">{{ $match->away_score ?? 0 }}</span>
            </div>

            <div class="flex flex-col items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 bg-rose-500 rounded-full animate-ping"></div>
                    <span id="live-indicator-text" class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.4em]">{{ $match->status == 'live' ? 'Live Match' : 'Live Console' }}</span>
                </div>
                
                <div class="flex items-center gap-2">
                    @if(!$match->started_at)
                    <button id="start-match-btn" onclick="startMatch()" class="bg-rose-500 text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-rose-600 transition-all hover:scale-105 active:scale-95">
                        START MATCH
                    </button>
                    @endif
                    
                    @if($match->started_at && $match->status !== 'finished')
                    <button id="end-match-btn" onclick="endMatch()" class="bg-zinc-900 text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-zinc-800 transition-all hover:scale-105 active:scale-95">
                        END MATCH
                    </button>
                    @endif

                    <select onchange="updateMatchStatus(this.value)" class="bg-zinc-50 border border-zinc-100 px-3 py-2 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-[9px] tracking-widest cursor-pointer">
                        <option value="upcoming" {{ $match->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="live" {{ $match->status == 'live' ? 'selected' : '' }}>Live</option>
                        <option value="finished" {{ $match->status == 'finished' ? 'selected' : '' }}>Finished</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-6 flex-1 justify-end text-right">
            <div>
                <h2 class="text-2xl font-black text-primary tracking-tighter uppercase leading-none mb-1">{{ $match->awayTeam->name }}</h2>
                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Away Team</span>
            </div>
            @if($match->awayTeam->logo_url)
            <img src="{{ $match->awayTeam->logo_url }}" class="w-16 h-16 object-contain">
            @endif
        </div>
    </div>

    <!-- Interface Grid: 3 Columns -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        
        <!-- COLUMN 1: HOME TEAM CONTROL (4/12) -->
        <div class="xl:col-span-4 home-theme space-y-8 animate-slide-in">
            <div class="bg-blue-600 text-white p-5 rounded-3xl text-center font-black text-xl tracking-widest uppercase shadow-xl">
                HOME CONTROL
            </div>

            <!-- Stat Buttons Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
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
                        ['id' => 'missed_chances', 'label' => 'Missed', 'full' => true],
                    ];
                @endphp

                @foreach($stats as $stat)
                <button onclick="updateStat('home', '{{ $stat['id'] }}')" class="stat-btn btn-stat-box {{ isset($stat['full']) ? 'col-span-2' : '' }}">
                    <span class="text-[9px] opacity-80">{{ $stat['label'] }}</span>
                    <div id="home-{{ $stat['id'] }}-count" class="count-badge text-xl leading-none mt-2">{{ $match->{'home_'.$stat['id']} }}</div>
                </button>
                @endforeach
            </div>

            <!-- Player List -->
            <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-zinc-100">
                <h3 class="text-[11px] font-black text-blue-600 uppercase tracking-[0.2em] border-b border-blue-50 pb-3 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                    Starting XI
                </h3>
                <div class="space-y-2.5">
                    @foreach($homeXI as $player)
                    <div class="group bg-zinc-50 hover:bg-white p-3 rounded-2xl border border-transparent hover:border-blue-100 flex items-center justify-between transition-all hover:shadow-md">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 bg-white rounded-lg flex items-center justify-center text-[10px] font-black text-blue-600 shadow-sm">{{ $player->pivot->shirt_number }}</span>
                            <span class="text-[11px] font-black text-primary uppercase tracking-tight">{{ $player->name }}</span>
                        </div>
                        <div class="flex gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity">
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'goal')" class="px-2.5 py-1.5 bg-blue-600 text-white text-[8px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 transition-transform shadow-md">G</button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'penalty_goal')" class="px-2.5 py-1.5 bg-emerald-500 text-white text-[8px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 transition-transform shadow-md">PG</button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'yellow_card')" class="px-1.5 py-1.5 bg-amber-400 text-white font-black rounded-lg hover:scale-105 active:scale-95 transition-transform shadow-md"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg></button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'red_card')" class="px-1.5 py-1.5 bg-rose-600 text-white font-black rounded-lg hover:scale-105 active:scale-95 transition-transform shadow-md"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg></button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h3 class="text-[11px] font-black text-blue-600 uppercase tracking-[0.2em] border-b border-blue-50 pb-3 mb-4 mt-8 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                    Bench
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($homeSubs as $player)
                    <div class="bg-zinc-50 p-2.5 rounded-xl border border-zinc-100 flex items-center justify-between hover:bg-white hover:border-blue-50 transition-colors">
                        <span class="text-[10px] font-bold text-primary uppercase truncate pr-1">{{ $player->name }}</span>
                        <div class="flex gap-1 shrink-0">
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'goal')" class="w-5 h-5 flex items-center justify-center bg-blue-100 text-blue-600 text-[8px] font-black rounded-md uppercase hover:bg-blue-600 hover:text-white transition-colors">G</button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'sub_on')" class="w-7 h-5 flex items-center justify-center bg-emerald-100 text-emerald-600 text-[8px] font-black rounded-md uppercase hover:bg-emerald-600 hover:text-white transition-colors">IN</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- COLUMN 2: AWAY TEAM CONTROL (4/12) -->
        <div class="xl:col-span-4 away-theme space-y-8 animate-slide-in" style="animation-delay: 0.1s">
            <div class="bg-red-600 text-white p-5 rounded-3xl text-center font-black text-xl tracking-widest uppercase shadow-xl">
                AWAY CONTROL
            </div>

            <!-- Stat Buttons Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($stats as $stat)
                <button onclick="updateStat('away', '{{ $stat['id'] }}')" class="stat-btn btn-stat-box {{ isset($stat['full']) ? 'col-span-2' : '' }}">
                    <span class="text-[9px] opacity-80">{{ $stat['label'] }}</span>
                    <div id="away-{{ $stat['id'] }}-count" class="count-badge text-xl leading-none mt-2">{{ $match->{'away_'.$stat['id']} }}</div>
                </button>
                @endforeach
            </div>

            <!-- Player List -->
            <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-zinc-100">
                <h3 class="text-[11px] font-black text-red-600 uppercase tracking-[0.2em] border-b border-red-50 pb-3 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-red-600 rounded-full"></span>
                    Starting XI
                </h3>
                <div class="space-y-2.5">
                    @foreach($awayXI as $player)
                    <div class="group bg-zinc-50 hover:bg-white p-3 rounded-2xl border border-transparent hover:border-red-100 flex items-center justify-between transition-all hover:shadow-md">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 bg-white rounded-lg flex items-center justify-center text-[10px] font-black text-red-600 shadow-sm">{{ $player->pivot->shirt_number }}</span>
                            <span class="text-[11px] font-black text-primary uppercase tracking-tight">{{ $player->name }}</span>
                        </div>
                        <div class="flex gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity">
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'goal')" class="px-2.5 py-1.5 bg-red-600 text-white text-[8px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 transition-transform shadow-md">G</button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'penalty_goal')" class="px-2.5 py-1.5 bg-emerald-500 text-white text-[8px] font-black rounded-lg uppercase tracking-wider hover:scale-105 active:scale-95 transition-transform shadow-md">PG</button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'yellow_card')" class="px-1.5 py-1.5 bg-amber-400 text-white font-black rounded-lg hover:scale-105 active:scale-95 transition-transform shadow-md"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg></button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'red_card')" class="px-1.5 py-1.5 bg-rose-600 text-white font-black rounded-lg hover:scale-105 active:scale-95 transition-transform shadow-md"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg></button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h3 class="text-[11px] font-black text-red-600 uppercase tracking-[0.2em] border-b border-red-50 pb-3 mb-4 mt-8 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-red-600 rounded-full"></span>
                    Bench
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($awaySubs as $player)
                    <div class="bg-zinc-50 p-2.5 rounded-xl border border-zinc-100 flex items-center justify-between hover:bg-white hover:border-red-50 transition-colors">
                        <span class="text-[10px] font-bold text-primary uppercase truncate pr-1">{{ $player->name }}</span>
                        <div class="flex gap-1 shrink-0">
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'goal')" class="w-5 h-5 flex items-center justify-center bg-red-100 text-red-600 text-[8px] font-black rounded-md uppercase hover:bg-red-600 hover:text-white transition-colors">G</button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'sub_on')" class="w-7 h-5 flex items-center justify-center bg-emerald-100 text-emerald-600 text-[8px] font-black rounded-md uppercase hover:bg-emerald-600 hover:text-white transition-colors">IN</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- COLUMN 3: LIVE TIMELINE (4/12) -->
        <div class="xl:col-span-4 h-full animate-slide-in" style="animation-delay: 0.2s">
            <div class="bg-white rounded-[3rem] p-8 shadow-xl border border-zinc-100 h-full flex flex-col sticky top-8">
                <div class="flex items-center justify-between mb-8 border-b border-zinc-50 pb-6 shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary text-secondary rounded-2xl flex items-center justify-center font-black shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-primary uppercase tracking-tight">Timeline</h3>
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest leading-none">Match Events</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Active</span>
                    </div>
                </div>

                <div id="event-feed" class="flex-1 space-y-4 max-h-[1000px] overflow-y-auto pr-4 no-scrollbar pb-12">
                    @foreach($match->matchEvents->sortByDesc('created_at') as $event)
                    <div class="flex items-start gap-4 p-4 rounded-3xl bg-zinc-50 border border-zinc-100 group hover:border-primary/20 hover:bg-white transition-all hover:shadow-md animate-slide-in">
                        <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-zinc-100 flex items-center justify-center font-black text-primary italic text-sm group-hover:scale-110 transition-transform">
                            {{ $event->minute }}'
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[9px] font-black uppercase tracking-[0.2em] {{ $event->team_id == $match->home_team_id ? 'text-blue-600' : 'text-red-600' }}">
                                    {{ $event->team_id == $match->home_team_id ? $match->homeTeam->name : $match->awayTeam->name }}
                                </span>
                            </div>
                            <div class="text-[13px] font-black text-primary uppercase tracking-tight leading-tight">
                                @if(in_array($event->event_type, ['goal', 'penalty_goal']))
                                    <span class="text-emerald-500">GOAL!</span> 
                                @endif
                                {{ str_replace('_', ' ', $event->event_type) }} 
                                @if($event->player_name)
                                    <div class="text-[11px] text-zinc-400 font-bold mt-0.5 tracking-normal">
                                        {{ $event->player_name }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-[9px] font-black text-zinc-300 uppercase italic">
                            {{ $event->created_at->format('H:i:s') }}
                        </div>
                    </div>
                    @endforeach

                    @if($match->matchEvents->isEmpty())
                    <div id="no-events-msg" class="text-center py-24">
                        <div class="w-16 h-16 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-zinc-100">
                             <svg class="w-8 h-8 text-zinc-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-[10px] font-black text-zinc-300 uppercase tracking-[0.3em]">Awaiting Kickoff</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let matchStartedAt = @json($match->started_at ? $match->started_at->toIso8601String() : null);
    let timerInterval;

    function updateTimer() {
        if (!matchStartedAt) return;
        
        const start = new Date(matchStartedAt);
        const now = new Date();
        const diff = Math.floor((now - start) / 1000); // seconds
        
        if (diff < 0) return;

        const mins = Math.floor(diff / 60);
        const secs = diff % 60;
        
        document.getElementById('match-timer').textContent = 
            `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    if (matchStartedAt) {
        timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
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
                document.getElementById('start-match-btn')?.remove();
                timerInterval = setInterval(updateTimer, 1000);
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
        const now = new Date();
        return Math.floor((now - start) / 60000) + 1; // Current minute
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
        const isGoal = ['goal', 'penalty_goal'].includes(event.event_type);

        const eventHtml = `
            <div class="flex items-start gap-4 p-4 rounded-3xl bg-zinc-50 border border-zinc-100 group hover:border-primary/20 hover:bg-white transition-all hover:shadow-md animate-slide-in">
                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-zinc-100 flex items-center justify-center font-black text-primary italic text-sm group-hover:scale-110 transition-transform">
                    ${event.minute}'
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] ${teamClass}">
                            ${teamName}
                        </span>
                    </div>
                    <div class="text-[13px] font-black text-primary uppercase tracking-tight leading-tight">
                        ${isGoal ? '<span class="text-emerald-500">GOAL!</span> ' : ''}
                        ${event.event_type.replace('_', ' ')} 
                        ${event.player_name ? `<div class="text-[11px] text-zinc-400 font-bold mt-0.5 tracking-normal">${event.player_name}</div>` : ''}
                    </div>
                </div>
                <div class="text-[9px] font-black text-zinc-300 uppercase italic">
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
