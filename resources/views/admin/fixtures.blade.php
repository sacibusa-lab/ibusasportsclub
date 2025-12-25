@extends('admin.layout')

@section('title', 'Fixture Creation')

@section('content')
<div class="space-y-12">
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
        <h3 class="text-xl font-black mb-8 italic uppercase tracking-tighter text-zinc-400">Create New Fixture</h3>

        @if($errors->any())
            <div class="mb-8 bg-rose-50 border border-rose-100 p-4 rounded-2xl">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                    <span class="text-rose-700 text-xs font-black uppercase tracking-widest">Validation Error</span>
                </div>
                <ul class="list-disc list-inside text-[10px] text-rose-600 font-bold uppercase tracking-tight">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.fixtures.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-4">
                    <label class="block text-xs font-black text-primary uppercase tracking-widest border-b border-zinc-50 pb-2">Teams</label>
                    <div class="flex items-center gap-4">
                        <select name="home_team_id" class="flex-1 bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                            <option value="">Home Team</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-zinc-300 font-black">VS</span>
                        <select name="away_team_id" class="flex-1 bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                            <option value="">Away Team</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-xs font-black text-primary uppercase tracking-widest border-b border-zinc-50 pb-2">Match Info</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="datetime-local" name="match_date" class="bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" required>
                        <input type="text" name="venue" placeholder="Venue Name" class="bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                        
                        <select name="referee_id" class="bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                            <option value="">Select Referee</option>
                            @foreach($referees as $referee)
                                <option value="{{ $referee->id }}">{{ $referee->name }} {{ $referee->has_fifa_badge ? '(FIFA)' : '' }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="attendance" placeholder="Expected Attendance" class="bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                        
                        <select name="referee_ar1_id" class="bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                            <option value="">Select Assistant 1</option>
                            @foreach($referees as $referee)
                                <option value="{{ $referee->id }}">{{ $referee->name }} {{ $referee->has_fifa_badge ? '(FIFA)' : '' }}</option>
                            @endforeach
                        </select>

                        <select name="referee_ar2_id" class="bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                            <option value="">Select Assistant 2</option>
                            @foreach($referees as $referee)
                                <option value="{{ $referee->id }}">{{ $referee->name }} {{ $referee->has_fifa_badge ? '(FIFA)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-8 border-t border-zinc-50">
                <div class="flex gap-4">
                    <select name="competition_id" class="bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs w-64" required>
                        <option value="">Select Competition</option>
                        @foreach($competitions as $competition)
                            <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                        @endforeach
                    </select>

                    <select name="stage" class="bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs w-48" required>
                        <option value="group">Group Stage</option>
                        <option value="semifinal">Semi-Final</option>
                        <option value="final">Grand Final</option>
                        <option value="novelty">Novelty Match</option>
                    </select>
                </div>
                <button type="submit" class="bg-secondary text-primary font-black px-12 py-4 rounded-2xl hover:scale-105 transition uppercase tracking-widest text-xs shadow-lg">Create Fixture</button>
            </div>
        </form>
    </div>

    <section class="space-y-6">
        <h3 class="text-xl font-black italic uppercase tracking-tighter text-zinc-400">Manage Fixtures</h3>
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-zinc-50 text-zinc-400 text-[10px] font-black uppercase tracking-widest border-b border-zinc-100">
                        <th class="px-6 py-4">Match</th>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Venue</th>
                        <th class="px-6 py-4">Stage</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @foreach($allMatches as $match)
                    <tr class="hover:bg-zinc-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-extrabold text-primary text-sm uppercase tracking-tighter">{{ $match->homeTeam->name }} v {{ $match->awayTeam->name }}</span>
                                @if($match->status === 'finished')
                                <span class="text-zinc-400 font-black text-[10px]">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-zinc-500 text-xs font-bold uppercase">{{ $match->match_date->format('D j M, H:i') }}</td>
                        <td class="px-6 py-4 text-zinc-500 text-xs font-bold uppercase">{{ $match->venue }}</td>
                        <td class="px-6 py-4">
                            <span class="text-[9px] font-black px-2 py-1 rounded bg-zinc-100 text-zinc-500 uppercase tracking-widest">{{ $match->stage }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block w-2 h-2 rounded-full {{ $match->status === 'finished' ? 'bg-secondary' : 'bg-accent animate-pulse' }} mr-1"></span>
                            <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">{{ $match->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2 text-zinc-300">
                                <a href="{{ route('admin.fixtures.edit', $match->id) }}" class="p-2 hover:text-primary transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.fixtures.destroy', $match->id) }}" method="POST" onsubmit="return confirm('Remove this fixture?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:text-accent transition" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

    @if($errors->any())
    <div class="mt-8 bg-accent/10 border border-accent/20 p-6 rounded-2xl">
        <ul class="list-disc list-inside text-accent font-bold text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
