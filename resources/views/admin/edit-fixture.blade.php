@extends('admin.layout')

@section('title', 'Edit Fixture')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border: 1px solid #f4f4f5;
        background: white;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        padding: 1rem;
    }
    .ql-container.ql-snow {
        border: 1px solid #f4f4f5;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
        background: #fafafa;
    }
    .ql-editor {
        font-family: inherit;
        font-size: 14px;
        min-height: 250px;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
        <form id="fixtureForm" action="{{ route('admin.fixtures.update', $match->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-emerald-700 text-xs font-bold uppercase tracking-widest">{{ session('success') }}</span>
                </div>
            @endif

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Competition</label>
                <select name="competition_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                    @foreach($competitions as $competition)
                        <option value="{{ $competition->id }}" {{ $match->competition_id == $competition->id ? 'selected' : '' }}>{{ $competition->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Home Team</label>
                    <select name="home_team_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $match->home_team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Away Team</label>
                    <select name="away_team_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $match->away_team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Match Date & Time</label>
                    <input type="datetime-local" name="match_date" value="{{ $match->match_date->format('Y-m-d\TH:i') }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Venue</label>
                    <input type="text" name="venue" value="{{ $match->venue }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Tournament Stage</label>
                    <select name="stage" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                        <option value="group" {{ $match->stage == 'group' ? 'selected' : '' }}>Group Stage</option>
                        <option value="semifinal" {{ $match->stage == 'semifinal' ? 'selected' : '' }}>Semi-Final</option>
                        <option value="final" {{ $match->stage == 'final' ? 'selected' : '' }}>Grand Final</option>
                        <option value="novelty" {{ $match->stage == 'novelty' ? 'selected' : '' }}>Novelty Match</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Match Status</label>
                    <select name="status" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                        <option value="upcoming" {{ $match->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="finished" {{ $match->status == 'finished' ? 'selected' : '' }}>Finished</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Matchday</label>
                    <input type="number" name="matchday" value="{{ $match->matchday }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" placeholder="e.g. 1">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Broadcaster Logo URL</label>
                    <input type="text" name="broadcaster_logo" value="{{ $match->broadcaster_logo }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" placeholder="https://example.com/logo.png">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Referee</label>
                    <select name="referee_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                        <option value="">Select Referee</option>
                        @foreach($referees as $referee)
                            <option value="{{ $referee->id }}" {{ $match->referee_id == $referee->id ? 'selected' : '' }}>
                                {{ $referee->name }} {{ $referee->has_fifa_badge ? '(FIFA)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Assistant Ref 1</label>
                    <select name="referee_ar1_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                        <option value="">Select Assistant 1</option>
                        @foreach($referees as $referee)
                            <option value="{{ $referee->id }}" {{ $match->referee_ar1_id == $referee->id ? 'selected' : '' }}>
                                {{ $referee->name }} {{ $referee->has_fifa_badge ? '(FIFA)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Assistant Ref 2</label>
                     <select name="referee_ar2_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                        <option value="">Select Assistant 2</option>
                        @foreach($referees as $referee)
                            <option value="{{ $referee->id }}" {{ $match->referee_ar2_id == $referee->id ? 'selected' : '' }}>
                                {{ $referee->name }} {{ $referee->has_fifa_badge ? '(FIFA)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Attendance</label>
                    <input type="number" name="attendance" value="{{ $match->attendance }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" placeholder="e.g. 50000">
                </div>
            </div>

            <!-- Lineup Selection (Stable Plain HTML) -->
            <div class="mt-12 space-y-8">
                <div class="flex items-center justify-between border-b border-zinc-50 pb-4">
                    <h3 class="text-xs font-black text-primary uppercase tracking-widest">Match Squads & Positions</h3>
                    <div class="flex gap-4 text-[9px] font-bold text-zinc-400 uppercase tracking-widest">
                        <span>● Start</span>
                        <span>○ Sub</span>
                        <span>✕ Out</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Home Team Players -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ $match->homeTeam->name }}</h4>
                            <span class="text-[9px] font-bold text-zinc-300 uppercase tracking-widest">Manager: {{ $match->homeTeam->manager }}</span>
                        </div>
                        <div class="bg-zinc-50 rounded-3xl p-2 max-h-[500px] overflow-y-auto no-scrollbar border border-zinc-100">
                            <table class="w-full">
                                <tbody class="divide-y divide-zinc-100">
                                    @php 
                                        $homeLineup = $match->lineups->where('pivot.team_id', $match->home_team_id);
                                    @endphp
                                    @foreach($match->homeTeam->players as $player)
                                        @php 
                                            $pLineup = $homeLineup->where('id', $player->id)->first();
                                            $status = $pLineup ? ($pLineup->pivot->is_substitute ? 'sub' : 'start') : 'none';
                                            $savedX = $pLineup->pivot->position_x ?? null;
                                            $savedY = $pLineup->pivot->position_y ?? null;
                                            
                                            $currentPos = $pLineup->pivot->position_key ?? 'GK';
                                        @endphp
                                        <tr class="group hover:bg-white transition">
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-[10px] font-black text-zinc-300 w-4">#{{ $player->shirt_number }}</span>
                                                    <span class="text-xs font-bold text-primary uppercase">{{ $player->name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-2">
                                                <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-zinc-200">
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="lineup[{{ $player->id }}][status]" value="start" {{ $status == 'start' ? 'checked' : '' }} class="hidden peer">
                                                        <div class="w-6 h-6 rounded-md flex items-center justify-center text-[10px] font-black peer-checked:bg-indigo-600 peer-checked:text-white text-zinc-300 hover:bg-zinc-50 transition">XI</div>
                                                    </label>
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="lineup[{{ $player->id }}][status]" value="sub" {{ $status == 'sub' ? 'checked' : '' }} class="hidden peer">
                                                        <div class="w-6 h-6 rounded-md flex items-center justify-center text-[10px] font-black peer-checked:bg-amber-500 peer-checked:text-white text-zinc-300 hover:bg-zinc-50 transition">S</div>
                                                    </label>
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="lineup[{{ $player->id }}][status]" value="none" {{ $status == 'none' ? 'checked' : '' }} class="hidden peer">
                                                        <div class="w-6 h-6 rounded-md flex items-center justify-center text-[10px] font-black peer-checked:bg-zinc-200 peer-checked:text-zinc-600 text-zinc-300 hover:bg-zinc-50 transition">✕</div>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <select name="lineup[{{ $player->id }}][position]" class="bg-white border border-zinc-200 rounded-lg text-[9px] font-black uppercase p-1.5 focus:ring-1 focus:ring-indigo-500 outline-none w-20">
                                                    @foreach($positions as $key => $coords)
                                                        <option value="{{ $key }}" {{ $currentPos == $key ? 'selected' : '' }}>{{ $key }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Away Team Players -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-[10px] font-black text-rose-600 uppercase tracking-widest">{{ $match->awayTeam->name }}</h4>
                            <span class="text-[9px] font-bold text-zinc-300 uppercase tracking-widest">Manager: {{ $match->awayTeam->manager }}</span>
                        </div>
                        <div class="bg-zinc-50 rounded-3xl p-2 max-h-[500px] overflow-y-auto no-scrollbar border border-zinc-100">
                            <table class="w-full">
                                <tbody class="divide-y divide-zinc-100">
                                    @php 
                                        $awayLineup = $match->lineups->where('pivot.team_id', $match->away_team_id);
                                    @endphp
                                    @foreach($match->awayTeam->players as $player)
                                        @php 
                                            $pLineup = $awayLineup->where('id', $player->id)->first();
                                            $status = $pLineup ? ($pLineup->pivot->is_substitute ? 'sub' : 'start') : 'none';
                                            $savedX = $pLineup->pivot->position_x ?? null;
                                            $savedY = $pLineup->pivot->position_y ?? null;
                                            
                                            $currentPos = $pLineup->pivot->position_key ?? 'GK';
                                        @endphp
                                        <tr class="group hover:bg-white transition">
                                            <td class="py-3 px-4 text-right">
                                                <div class="flex items-center justify-end gap-3">
                                                    <span class="text-xs font-bold text-primary uppercase">{{ $player->name }}</span>
                                                    <span class="text-[10px] font-black text-zinc-300 w-4">#{{ $player->shirt_number }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-2">
                                                <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-zinc-200">
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="lineup[{{ $player->id }}][status]" value="start" {{ $status == 'start' ? 'checked' : '' }} class="hidden peer">
                                                        <div class="w-6 h-6 rounded-md flex items-center justify-center text-[10px] font-black peer-checked:bg-rose-600 peer-checked:text-white text-zinc-300 hover:bg-zinc-50 transition">XI</div>
                                                    </label>
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="lineup[{{ $player->id }}][status]" value="sub" {{ $status == 'sub' ? 'checked' : '' }} class="hidden peer">
                                                        <div class="w-6 h-6 rounded-md flex items-center justify-center text-[10px] font-black peer-checked:bg-amber-500 peer-checked:text-white text-zinc-300 hover:bg-zinc-50 transition">S</div>
                                                    </label>
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="lineup[{{ $player->id }}][status]" value="none" {{ $status == 'none' ? 'checked' : '' }} class="hidden peer">
                                                        <div class="w-6 h-6 rounded-md flex items-center justify-center text-[10px] font-black peer-checked:bg-zinc-200 peer-checked:text-zinc-600 text-zinc-300 hover:bg-zinc-50 transition">✕</div>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <select name="lineup[{{ $player->id }}][position]" class="bg-white border border-zinc-200 rounded-lg text-[9px] font-black uppercase p-1.5 focus:ring-1 focus:ring-rose-500 outline-none w-20">
                                                    @foreach($positions as $key => $coords)
                                                        <option value="{{ $key }}" {{ $currentPos == $key ? 'selected' : '' }}>{{ $key }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Match Stats -->
            <div class="grid grid-cols-2 gap-12 bg-zinc-50 p-8 rounded-3xl border border-zinc-100 mt-12">
                <!-- Home Stats -->
                <div class="space-y-6">
                    <div class="text-center pb-4 border-b border-zinc-100">
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Home Score</label>
                        <input type="number" name="home_score" value="{{ $match->home_score }}" class="w-24 bg-white border border-zinc-200 p-4 rounded-2xl font-black text-primary text-3xl text-center focus:ring-2 focus:ring-secondary outline-none transition shadow-sm" min="0">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Possession %</label>
                            <input type="number" name="home_possession" value="{{ $match->home_possession }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0" max="100">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Shots</label>
                            <input type="number" name="home_shots" value="{{ $match->home_shots }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Corners</label>
                            <input type="number" name="home_corners" value="{{ $match->home_corners }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Offsides</label>
                            <input type="number" name="home_offsides" value="{{ $match->home_offsides }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Fouls</label>
                            <input type="number" name="home_fouls" value="{{ $match->home_fouls }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Free Kicks</label>
                            <input type="number" name="home_free_kicks" value="{{ $match->home_free_kicks }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Throw-ins</label>
                            <input type="number" name="home_throw_ins" value="{{ $match->home_throw_ins }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">GK Saves</label>
                            <input type="number" name="home_saves" value="{{ $match->home_saves }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Goal Kicks</label>
                            <input type="number" name="home_goal_kicks" value="{{ $match->home_goal_kicks }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-rose-500 uppercase tracking-widest">Missed Chances</label>
                            <input type="number" name="home_missed_chances" value="{{ $match->home_missed_chances }}" class="w-full bg-white border border-rose-100 p-3 rounded-xl font-bold text-rose-600 text-xs" min="0">
                        </div>
                    </div>
                </div>

                <!-- Away Stats -->
                <div class="space-y-6">
                    <div class="text-center pb-4 border-b border-zinc-100">
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Away Score</label>
                        <input type="number" name="away_score" value="{{ $match->away_score }}" class="w-24 bg-white border border-zinc-200 p-4 rounded-2xl font-black text-primary text-3xl text-center focus:ring-2 focus:ring-secondary outline-none transition shadow-sm" min="0">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Possession %</label>
                            <input type="number" name="away_possession" value="{{ $match->away_possession }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0" max="100">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Shots</label>
                            <input type="number" name="away_shots" value="{{ $match->away_shots }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Corners</label>
                            <input type="number" name="away_corners" value="{{ $match->away_corners }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Offsides</label>
                            <input type="number" name="away_offsides" value="{{ $match->away_offsides }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Fouls</label>
                            <input type="number" name="away_fouls" value="{{ $match->away_fouls }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Free Kicks</label>
                            <input type="number" name="away_free_kicks" value="{{ $match->away_free_kicks }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Throw-ins</label>
                            <input type="number" name="away_throw_ins" value="{{ $match->away_throw_ins }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">GK Saves</label>
                            <input type="number" name="away_saves" value="{{ $match->away_saves }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Goal Kicks</label>
                            <input type="number" name="away_goal_kicks" value="{{ $match->away_goal_kicks }}" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-xs" min="0">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-rose-500 uppercase tracking-widest">Missed Chances</label>
                            <input type="number" name="away_missed_chances" value="{{ $match->away_missed_chances }}" class="w-full bg-white border border-rose-100 p-3 rounded-xl font-bold text-rose-600 text-xs" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-2 mt-8">
                <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Match Report / Commentary</label>
                <div id="editor-container" class="bg-zinc-50 rounded-2xl overflow-hidden border border-zinc-100 shadow-sm mt-2">
                    <div id="report-editor">{!! $match->report !!}</div>
                </div>
                <input type="hidden" name="report" id="report-input">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8 bg-zinc-50 p-6 rounded-3xl border border-zinc-100">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Match Highlights Video URL (YouTube/Vimeo)</label>
                    <input type="text" name="highlights_url" value="{{ $match->highlights_url }}" class="w-full bg-white border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" placeholder="https://www.youtube.com/watch?v=...">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Highlights Thumbnail</label>
                    <div class="flex items-center gap-4">
                        @if($match->highlights_thumbnail)
                            <img src="{{ $match->highlights_thumbnail }}" class="w-20 h-12 object-cover rounded-xl border border-zinc-200">
                        @endif
                        <input type="file" name="highlights_thumbnail" class="flex-1 bg-white border border-zinc-100 p-3 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="space-y-4 bg-secondary/5 p-8 rounded-3xl border border-secondary/10">
                <label class="block text-[10px] font-black text-primary uppercase tracking-widest border-b border-secondary/20 pb-2">Man of the Match Award 🏆</label>
                <select name="motm_player_id" class="w-full bg-white border border-secondary/20 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-secondary outline-none transition uppercase text-xs shadow-sm">
                    <option value="">Select Man of the Match</option>
                    <optgroup label="{{ $match->homeTeam->name }}">
                        @foreach($match->homeTeam->players as $p)
                            <option value="{{ $p->id }}" {{ $match->motm_player_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="{{ $match->awayTeam->name }}">
                        @foreach($match->awayTeam->players as $p)
                            <option value="{{ $p->id }}" {{ $match->motm_player_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div class="flex items-center gap-4 pt-6 pb-6 border-b border-zinc-50 mb-6">
                <button type="submit" class="flex-1 bg-primary text-secondary font-black py-5 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-xs shadow-lg">Save Match Data & Lineups</button>
                
                @if($match->status === 'upcoming' && !$match->prediction_closes_at)
                <button type="submit" form="startMatchForm" class="flex-1 bg-emerald-500 text-white font-black py-5 rounded-2xl hover:bg-emerald-600 transition uppercase tracking-widest text-xs shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Start Match & Countdown
                </button>
                @elseif($match->prediction_closes_at && $match->prediction_closes_at->isFuture())
                <div class="flex-1 bg-emerald-50 text-emerald-600 font-black py-5 rounded-2xl border border-emerald-100 uppercase tracking-widest text-xs flex items-center justify-center gap-2 animate-pulse">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Predictions Closing Soon
                </div>
                @endif

                <a href="{{ route('admin.fixtures') }}" class="px-10 py-5 border border-zinc-200 rounded-2xl font-black text-[10px] text-zinc-400 uppercase tracking-widest hover:bg-zinc-50 transition">Cancel</a>
            </div>
        </form>

        <!-- Match Gallery Section -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100 mt-8">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-8 border-b border-zinc-50 pb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Match Gallery
                <span class="ml-auto text-[9px] text-zinc-400 font-bold">({{ $match->images->count() }} images)</span>
            </h3>

            <!-- Upload Form -->
            <form action="{{ route('admin.matches.gallery.upload', $match->id) }}" method="POST" enctype="multipart/form-data" class="mb-8">
                @csrf
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-zinc-200 rounded-2xl p-8 text-center bg-zinc-50 hover:bg-zinc-100 transition cursor-pointer" onclick="document.getElementById('galleryImages').click()">
                        <svg class="w-12 h-12 mx-auto text-zinc-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <p class="text-sm font-black text-zinc-400 uppercase tracking-widest">Click to select multiple images</p>
                        <p class="text-[10px] text-zinc-400 mt-2">or drag and drop</p>
                        <input type="file" id="galleryImages" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                    </div>
                    
                    <div id="imagePreview" class="grid grid-cols-4 gap-4 hidden"></div>
                    
                    <button type="submit" class="w-full bg-primary text-secondary font-black py-4 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-xs shadow-lg">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        Upload Selected Images
                    </button>
                </div>
            </form>

            <!-- Existing Gallery Images -->
            @if($match->images->count() > 0)
            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($match->images->sortBy('order') as $image)
                <div class="relative group">
                    <img src="{{ $image->image_url }}" alt="{{ $image->caption }}" class="w-full h-32 object-cover rounded-xl border border-zinc-100">
                    <form action="{{ route('admin.matches.gallery.delete', [$match->id, $image->id]) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this image?')" class="bg-red-500 text-white p-2 rounded-lg shadow-lg hover:bg-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                    @if($image->caption)
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 text-white text-[9px] p-2 rounded-b-xl">
                        {{ $image->caption }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-zinc-400 text-xs font-bold uppercase tracking-widest">
                No images in gallery yet
            </div>
            @endif
        </div>

        @if($match->status === 'upcoming' && !$match->prediction_closes_at)
        <form id="startMatchForm" action="{{ route('admin.matches.start', $match->id) }}" method="POST" class="hidden">
            @csrf
        </form>
        @endif

        <!-- Match Events Section -->
        <div class="mt-16 space-y-10">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest border-b border-zinc-50 pb-4">Timeline Events (Goals, Cards, etc.)</h3>
            
            <form id="matchEventForm" action="{{ route('admin.matches.events.store', $match->id) }}" method="POST" enctype="multipart/form-data" class="bg-zinc-50 p-8 rounded-3xl border border-zinc-100 space-y-6">
                @csrf
                <div class="grid grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Team</label>
                        <select name="team_id" class="w-full bg-white border border-zinc-200 p-4 rounded-xl font-bold text-primary text-xs" required>
                            <option value="{{ $match->home_team_id }}">{{ $match->homeTeam->name }}</option>
                            <option value="{{ $match->away_team_id }}">{{ $match->awayTeam->name }}</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Event Type</label>
                        <select id="eventTypeSelector" name="event_type" class="w-full bg-white border border-zinc-200 p-4 rounded-xl font-bold text-primary text-xs" required onchange="updateEventFields(this.value)">
                            <option value="goal">Goal</option>
                            <option value="penalty">Penalty</option>
                            <option value="yellow_card">Yellow Card</option>
                            <option value="red_card">Red Card</option>
                            <option value="sub_on">Substitution (On)</option>
                            <option value="sub_off">Substitution (Off)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Minute</label>
                        <input type="number" name="minute" class="w-full bg-white border border-zinc-200 p-4 rounded-xl font-bold text-primary text-xs" min="0" max="120" placeholder="e.g. 45" required>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label id="mainPlayerLabel" class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Main Player (Scorer/Carded)</label>
                        <select name="player_id" class="w-full bg-white border border-zinc-200 p-4 rounded-xl font-bold text-primary text-xs" required>
                            <option value="">Select Player</option>
                            <optgroup label="{{ $match->homeTeam->name }}">
                                @foreach($match->homeTeam->players as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="{{ $match->awayTeam->name }}">
                                @foreach($match->awayTeam->players as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <div id="assistField">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Assist By (Optional)</label>
                            <select name="assist_player_id" class="w-full bg-white border border-zinc-200 p-4 rounded-xl font-bold text-primary text-xs">
                                <option value="">No Assist</option>
                                <optgroup label="{{ $match->homeTeam->name }}">
                                    @foreach($match->homeTeam->players as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="{{ $match->awayTeam->name }}">
                                    @foreach($match->awayTeam->players as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div id="subField" style="display: none;">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Replacing (Player Off)</label>
                            <select name="related_player_id" class="w-full bg-white border border-zinc-200 p-4 rounded-xl font-bold text-primary text-xs">
                                <option value="">Select Player Off</option>
                                <optgroup label="{{ $match->homeTeam->name }}">
                                    @foreach($match->homeTeam->players as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="{{ $match->awayTeam->name }}">
                                    @foreach($match->awayTeam->players as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Photo (Overrides Profile Photo)</label>
                        <input type="file" name="player_image" class="w-full bg-white border border-zinc-200 p-4 rounded-xl font-bold text-primary text-[10px]" accept="image/*">
                    </div>
                </div>

                <button type="submit" class="w-full bg-secondary text-primary font-black py-4 rounded-2xl hover:bg-secondary-light transition uppercase tracking-widest text-xs shadow-md">Add Event to Timeline</button>
            </form>

            <script>
                function updateEventFields(type) {
                    const label = document.getElementById('mainPlayerLabel');
                    const assist = document.getElementById('assistField');
                    const sub = document.getElementById('subField');

                    if (type === 'sub_on') {
                        label.textContent = 'Player On';
                        assist.style.display = 'none';
                        sub.style.display = 'block';
                    } else if (type === 'sub_off') {
                        label.textContent = 'Player Off';
                        assist.style.display = 'none';
                        sub.style.display = 'none';
                    } else if (type === 'goal') {
                        label.textContent = 'Scorer';
                        assist.style.display = 'block';
                        sub.style.display = 'none';
                    } else {
                        label.textContent = 'Main Player (Scorer/Carded)';
                        assist.style.display = 'none';
                        sub.style.display = 'none';
                    }
                }

                document.getElementById('matchEventForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.textContent;

                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Adding Event...';

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add row to table
                            const tbody = document.getElementById('eventsTimelineBody');
                            const event = data.event;
                            
                            // Remove empty row if exists
                            const emptyRow = tbody.querySelector('.italic');
                            if (emptyRow) emptyRow.closest('tr').remove();

                            const newRow = `
                                <tr class="text-[11px] font-bold text-primary animate-fade-in transition bg-emerald-50/30">
                                    <td class="px-6 py-3">${event.minute}'</td>
                                    <td class="px-6 py-3">
                                        <span class="uppercase tracking-tighter">${event.type}</span>
                                    </td>
                                    <td class="px-6 py-3 uppercase">${event.team}</td>
                                    <td class="px-6 py-3 flex items-center gap-2">
                                        ${event.player_image 
                                            ? `<img src="${event.player_image}" class="w-6 h-6 rounded-full object-cover border border-zinc-100">`
                                            : `<div class="w-6 h-6 rounded-full bg-zinc-100 flex items-center justify-center text-[8px] text-zinc-400 uppercase">${event.initial}</div>`
                                        }
                                        <div class="flex flex-col">
                                            <span>${event.player_name}</span>
                                            ${event.extra ? `<span class="text-[8px] text-zinc-400">${event.extra}</span>` : ''}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <form action="${event.delete_url}" method="POST" onsubmit="return confirm('Remove this event?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-accent hover:text-red-700 transition">✕</button>
                                        </form>
                                    </td>
                                </tr>
                            `;
                            
                            tbody.insertAdjacentHTML('afterbegin', newRow);
                            
                            // Clear inputs
                            form.reset();
                            updateEventFields(document.getElementById('eventTypeSelector').value);
                            
                            // Show toast or alert
                            const alert = document.createElement('div');
                            alert.className = "fixed bottom-8 right-8 bg-primary text-secondary px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl z-[100] animate-bounce";
                            alert.textContent = "Event Added Successfully!";
                            document.body.appendChild(alert);
                            setTimeout(() => alert.remove(), 3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Something went wrong. Please try again.');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalBtnText;
                    });
                });

                // Initial call to set correct state
                document.addEventListener('DOMContentLoaded', () => {
                    updateEventFields(document.getElementById('eventTypeSelector').value);
                });
            </script>

            <div class="bg-white border border-zinc-100 rounded-2xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-zinc-50">
                        <tr class="text-[9px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100">
                            <th class="px-6 py-3">Min</th>
                            <th class="px-6 py-3">Event</th>
                            <th class="px-6 py-3">Team</th>
                            <th class="px-6 py-3">Player</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                <tbody id="eventsTimelineBody" class="divide-y divide-zinc-50">
                        @forelse($match->matchEvents as $event)
                        <tr class="text-[11px] font-bold text-primary">
                            <td class="px-6 py-3">{{ $event->minute }}'</td>
                            <td class="px-6 py-3">
                                <span class="uppercase tracking-tighter">{{ str_replace('_', ' ', $event->event_type) }}</span>
                            </td>
                            <td class="px-6 py-3 uppercase">{{ $event->team->name }}</td>
                            <td class="px-6 py-3 flex items-center gap-2">
                                @if($event->player_image_url)
                                <img src="{{ $event->player_image_url }}" class="w-6 h-6 rounded-full object-cover border border-zinc-100" alt="">
                                @else
                                <div class="w-6 h-6 rounded-full bg-zinc-100 flex items-center justify-center text-[8px] text-zinc-400 uppercase">
                                    {{ substr($event->player_name, 0, 1) }}
                                </div>
                                @endif
                                <div class="flex flex-col">
                                    <span>{{ $event->player_name }}</span>
                                    @if($event->event_type == 'goal' && $event->assistant)
                                        <span class="text-[8px] text-zinc-400">assist: {{ $event->assistant->name }}</span>
                                    @elseif($event->event_type == 'sub_on' && $event->relatedPlayer)
                                        <span class="text-[8px] text-zinc-400">replacing: {{ $event->relatedPlayer->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <form action="{{ route('admin.matches.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Remove this event?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-accent hover:text-red-700 transition">✕</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-zinc-300 italic">No events recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Match Gallery Section -->
        <div class="mt-16 pt-16 border-t border-zinc-100 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black text-primary uppercase tracking-widest">Match Gallery</h3>
                    <p class="text-[10px] text-zinc-400 mt-1 uppercase font-bold">Add photos or external links for this match</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Upload Form -->
                <div class="lg:col-span-1">
                    <form action="{{ route('admin.matches.gallery.store', $match->id) }}" method="POST" enctype="multipart/form-data" class="bg-zinc-50 p-6 rounded-3xl border border-zinc-100 space-y-4 shadow-sm">
                        @csrf
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Upload Image</label>
                            <input type="file" name="image" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-[10px]" accept="image/*">
                        </div>

                        <div class="relative py-2 flex items-center">
                            <div class="flex-grow border-t border-zinc-200"></div>
                            <span class="flex-shrink mx-4 text-[8px] font-black text-zinc-300 uppercase">OR</span>
                            <div class="flex-grow border-t border-zinc-200"></div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Image URL</label>
                            <input type="text" name="image_url" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-[10px]" placeholder="https://example.com/photo.jpg">
                        </div>

                        <div class="space-y-2 pt-2">
                            <label class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Caption (Optional)</label>
                            <input type="text" name="caption" class="w-full bg-white border border-zinc-200 p-3 rounded-xl font-bold text-primary text-[10px]" placeholder="Match action shot">
                        </div>

                        <button type="submit" class="w-full bg-primary text-secondary font-black py-4 rounded-xl hover:bg-primary-light transition uppercase tracking-widest text-[10px] shadow-md mt-2">Add to Gallery</button>
                    </form>
                </div>

                <!-- Gallery Display -->
                <div class="lg:col-span-2">
                    <div class="bg-zinc-50 p-6 rounded-3xl border border-zinc-100 min-h-[300px]">
                        @if($match->images->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($match->images as $image)
                                    <div class="group relative aspect-video bg-white rounded-2xl overflow-hidden border border-zinc-200 shadow-sm transition hover:shadow-md">
                                        <img src="{{ $image->image_url }}" class="w-full h-full object-cover" alt="{{ $image->caption }}">
                                        
                                        <!-- Overlay with Actions -->
                                        <div class="absolute inset-0 bg-primary/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                            <form action="{{ route('admin.matches.gallery.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Remove this image from gallery?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center hover:bg-red-700 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>

                                        @if($image->caption)
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-2">
                                                <p class="text-[8px] font-bold text-white truncate">{{ $image->caption }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-full flex flex-col items-center justify-center text-center p-12">
                                <div class="w-16 h-16 bg-zinc-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <h4 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">No images yet</h4>
                                <p class="text-[9px] text-zinc-300 mt-1">Upload match photos to showcase them to fans.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#report-editor', {
        theme: 'snow',
        placeholder: 'Enter detailed match report or live commentary...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });

    // Handle Image Upload using the news upload route
    quill.getModule('toolbar').addHandler('image', function() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            if (/^image\//.test(file.type)) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("admin.news.upload-image") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.url) {
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', result.url);
                    }
                })
                .catch(error => {
                    console.error('Error uploading image:', error);
                    alert('Image upload failed.');
                });
            }
        };
    });

    document.getElementById('fixtureForm').onsubmit = function() {
        var report = document.querySelector('#report-input');
        report.value = quill.root.innerHTML;
        
        // Optional: validation could go here
    };

    function previewImages(input) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        preview.classList.remove('hidden');
        
        if (input.files) {
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-xl border border-zinc-200">
                        <span class="absolute top-2 left-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">${index + 1}</span>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endpush
