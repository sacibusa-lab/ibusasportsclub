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
        padding: 1.5rem 1rem;
        border-radius: 1rem;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .btn-stat-box:hover {
        filter: brightness(1.1);
    }
    .count-badge {
        font-size: 1.5rem;
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto space-y-12">
    <!-- Header / Scoreboard -->
    <div class="bg-white rounded-[3rem] p-8 shadow-xl border border-zinc-100 flex items-center justify-between">
        <div class="flex items-center gap-6 flex-1">
            <img src="{{ $match->homeTeam->logo_url }}" class="w-20 h-20 object-contain">
            <div>
                <h2 class="text-3xl font-black text-primary tracking-tighter uppercase">{{ $match->homeTeam->name }}</h2>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Home Team</span>
            </div>
        </div>

        <div class="flex flex-col items-center gap-2">
            <div class="mb-4 flex flex-col items-center gap-4">
                <div id="match-timer" class="text-4xl font-black text-rose-500 tabular-nums">
                    00:00
                </div>
                <div class="flex items-center gap-2">
                    @if(!$match->started_at)
                    <button id="start-match-btn" onclick="startMatch()" class="bg-rose-500 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-rose-600 transition">
                        START MATCH
                    </button>
                    @endif
                    
                    @if($match->status === 'live')
                    <button id="end-match-btn" onclick="endMatch()" class="bg-zinc-900 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-zinc-800 transition">
                        END MATCH
                    </button>
                    @endif
                </div>
                <select onchange="updateMatchStatus(this.value)" class="bg-zinc-50 border border-zinc-100 p-2 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-[10px] tracking-widest">
                    <option value="upcoming" {{ $match->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="live" {{ $match->status == 'live' ? 'selected' : '' }}>Live</option>
                    <option value="finished" {{ $match->status == 'finished' ? 'selected' : '' }}>Finished</option>
                </select>
            </div>
            <div class="flex items-center gap-8">
                <span id="home-score" class="text-7xl font-black text-primary">{{ $match->home_score ?? 0 }}</span>
                <span class="text-4xl text-zinc-200">-</span>
                <span id="away-score" class="text-7xl font-black text-primary">{{ $match->away_score ?? 0 }}</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-rose-500 rounded-full animate-ping"></div>
                <span class="text-xs font-black text-zinc-400 uppercase tracking-[0.3em]">Live Console</span>
            </div>
        </div>

        <div class="flex items-center gap-6 flex-1 justify-end text-right">
            <div>
                <h2 class="text-3xl font-black text-primary tracking-tighter uppercase">{{ $match->awayTeam->name }}</h2>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Away Team</span>
            </div>
            <img src="{{ $match->awayTeam->logo_url }}" class="w-20 h-20 object-contain">
        </div>
    </div>

    <!-- Interface Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        
        <!-- HOME TEAM CONTROL -->
        <div class="home-theme space-y-12">
            <div class="bg-blue-600 text-white p-6 rounded-[2rem] text-center font-black text-2xl tracking-widest uppercase shadow-lg">
                HOME
            </div>

            <!-- Stat Buttons Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button onclick="updateStat('home', 'throw_ins')" class="stat-btn btn-stat-box">
                    THROW IN
                    <div id="home-throw_ins-count" class="count-badge">{{ $match->home_throw_ins }}</div>
                </button>
                <button onclick="updateStat('home', 'free_kicks')" class="stat-btn btn-stat-box">
                    FREE KICK
                    <div id="home-free_kicks-count" class="count-badge">{{ $match->home_free_kicks }}</div>
                </button>
                <button onclick="updateStat('home', 'fouls')" class="stat-btn btn-stat-box">
                    FOUL
                    <div id="home-fouls-count" class="count-badge">{{ $match->home_fouls }}</div>
                </button>
                <button onclick="updateStat('home', 'corners')" class="stat-btn btn-stat-box">
                    CORNER
                    <div id="home-corners-count" class="count-badge">{{ $match->home_corners }}</div>
                </button>
                <button onclick="updateStat('home', 'goal_kicks')" class="stat-btn btn-stat-box">
                    GOAL KICK
                    <div id="home-goal_kicks-count" class="count-badge">{{ $match->home_goal_kicks }}</div>
                </button>
                <button onclick="updateStat('home', 'offsides')" class="stat-btn btn-stat-box">
                    OFFSIDE
                    <div id="home-offsides-count" class="count-badge">{{ $match->home_offsides }}</div>
                </button>
                <button onclick="updateStat('home', 'shots')" class="stat-btn btn-stat-box">
                    SHOT ON TARGET
                    <div id="home-shots-count" class="count-badge">{{ $match->home_shots }}</div>
                </button>
                <button onclick="updateStat('home', 'saves')" class="stat-btn btn-stat-box">
                    SAVE
                    <div id="home-saves-count" class="count-badge">{{ $match->home_saves }}</div>
                </button>
                <div class="col-span-1">
                    <button onclick="updateStat('home', 'missed_chances')" class="w-full stat-btn btn-stat-box">
                        MISSED CHANCES
                        <div id="home-missed_chances-count" class="count-badge">{{ $match->home_missed_chances }}</div>
                    </button>
                </div>
            </div>

            <!-- Player List -->
            <div class="space-y-6">
                <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] border-b-2 border-blue-50 pb-2">Starting XI</h3>
                <div class="space-y-3">
                    @foreach($homeXI as $player)
                    <div class="bg-white p-4 rounded-2xl border border-zinc-100 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <span class="w-6 h-6 bg-zinc-100 rounded-lg flex items-center justify-center text-[10px] font-black text-zinc-400">{{ $player->pivot->shirt_number }}</span>
                            <span class="text-sm font-black text-primary uppercase">{{ $player->name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'goal')" class="px-4 py-2 bg-blue-600 text-white text-[9px] font-black rounded-lg uppercase tracking-widest hover:brightness-110">Goal</button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'penalty_goal')" class="px-4 py-2 bg-emerald-500 text-white text-[9px] font-black rounded-lg uppercase tracking-widest hover:brightness-110">P. Goal</button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'yellow_card')" class="px-4 py-2 bg-amber-400 text-white text-[9px] font-black rounded-lg uppercase tracking-widest hover:brightness-110">Yellow</button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'red_card')" class="px-4 py-2 bg-rose-600 text-white text-[9px] font-black rounded-lg uppercase tracking-widest hover:brightness-110">Red</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] border-b-2 border-blue-50 pb-2 pt-6">Substitutes</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($homeSubs as $player)
                    <div class="bg-white p-3 rounded-xl border border-zinc-100 flex items-center justify-between shadow-sm">
                        <span class="text-[11px] font-bold text-primary uppercase">{{ $player->name }}</span>
                        <div class="flex gap-1">
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'goal')" class="p-1 px-2 bg-blue-100 text-blue-600 text-[8px] font-black rounded-md uppercase">G</button>
                            <button onclick="logPlayerEvent({{ $match->home_team_id }}, {{ $player->id }}, 'sub_on')" class="p-1 px-2 bg-emerald-100 text-emerald-600 text-[8px] font-black rounded-md uppercase">IN</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- AWAY TEAM CONTROL -->
        <div class="away-theme space-y-12">
            <div class="bg-red-600 text-white p-6 rounded-[2rem] text-center font-black text-2xl tracking-widest uppercase shadow-lg">
                AWAY
            </div>

            <!-- Stat Buttons Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button onclick="updateStat('away', 'throw_ins')" class="stat-btn btn-stat-box">
                    THROW IN
                    <div id="away-throw_ins-count" class="count-badge">{{ $match->away_throw_ins }}</div>
                </button>
                <button onclick="updateStat('away', 'free_kicks')" class="stat-btn btn-stat-box">
                    FREE KICK
                    <div id="away-free_kicks-count" class="count-badge">{{ $match->away_free_kicks }}</div>
                </button>
                <button onclick="updateStat('away', 'fouls')" class="stat-btn btn-stat-box">
                    FOUL
                    <div id="away-fouls-count" class="count-badge">{{ $match->away_fouls }}</div>
                </button>
                <button onclick="updateStat('away', 'corners')" class="stat-btn btn-stat-box">
                    CORNER
                    <div id="away-corners-count" class="count-badge">{{ $match->away_corners }}</div>
                </button>
                <button onclick="updateStat('away', 'goal_kicks')" class="stat-btn btn-stat-box">
                    GOAL KICK
                    <div id="away-goal_kicks-count" class="count-badge">{{ $match->away_goal_kicks }}</div>
                </button>
                <button onclick="updateStat('away', 'offsides')" class="stat-btn btn-stat-box">
                    OFFSIDE
                    <div id="away-offsides-count" class="count-badge">{{ $match->away_offsides }}</div>
                </button>
                <button onclick="updateStat('away', 'shots')" class="stat-btn btn-stat-box">
                    SHOT ON TARGET
                    <div id="away-shots-count" class="count-badge">{{ $match->away_shots }}</div>
                </button>
                <button onclick="updateStat('away', 'saves')" class="stat-btn btn-stat-box">
                    SAVE
                    <div id="away-saves-count" class="count-badge">{{ $match->away_saves }}</div>
                </button>
                <div class="col-span-1">
                    <button onclick="updateStat('away', 'missed_chances')" class="w-full stat-btn btn-stat-box">
                        MISSED CHANCES
                        <div id="away-missed_chances-count" class="count-badge">{{ $match->away_missed_chances }}</div>
                    </button>
                </div>
            </div>

            <!-- Player List -->
            <div class="space-y-6">
                <h3 class="text-xs font-black text-red-600 uppercase tracking-[0.2em] border-b-2 border-red-50 pb-2">Starting XI</h3>
                <div class="space-y-3">
                    @foreach($awayXI as $player)
                    <div class="bg-white p-4 rounded-2xl border border-zinc-100 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <span class="w-6 h-6 bg-zinc-100 rounded-lg flex items-center justify-center text-[10px] font-black text-zinc-400">{{ $player->pivot->shirt_number }}</span>
                            <span class="text-sm font-black text-primary uppercase">{{ $player->name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'goal')" class="px-4 py-2 bg-red-600 text-white text-[9px] font-black rounded-lg uppercase tracking-widest hover:brightness-110">Goal</button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'penalty_goal')" class="px-4 py-2 bg-emerald-500 text-white text-[9px] font-black rounded-lg uppercase tracking-widest hover:brightness-110">P. Goal</button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'yellow_card')" class="px-4 py-2 bg-amber-400 text-white text-[9px] font-black rounded-lg uppercase tracking-widest hover:brightness-110">Yellow</button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'red_card')" class="px-4 py-2 bg-rose-600 text-white text-[9px] font-black rounded-lg uppercase tracking-widest hover:brightness-110">Red</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h3 class="text-xs font-black text-red-600 uppercase tracking-[0.2em] border-b-2 border-red-50 pb-2 pt-6">Substitutes</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($awaySubs as $player)
                    <div class="bg-white p-3 rounded-xl border border-zinc-100 flex items-center justify-between shadow-sm">
                        <span class="text-[11px] font-bold text-primary uppercase">{{ $player->name }}</span>
                        <div class="flex gap-1">
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'goal')" class="p-1 px-2 bg-red-100 text-red-600 text-[8px] font-black rounded-md uppercase">G</button>
                            <button onclick="logPlayerEvent({{ $match->away_team_id }}, {{ $player->id }}, 'sub_on')" class="p-1 px-2 bg-emerald-100 text-emerald-600 text-[8px] font-black rounded-md uppercase">IN</button>
                        </div>
                    </div>
                    @endforeach
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
                document.getElementById(`${side}-${stat}-count`).textContent = data.new_value;
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
                if (type === 'goal') {
                    document.getElementById('home-score').textContent = data.home_score;
                    document.getElementById('away-score').textContent = data.away_score;
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
