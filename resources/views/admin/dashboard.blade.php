@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-12">
    <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-3xl border border-zinc-200 shadow-sm">
            <span class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Total Teams</span>
            <span class="text-4xl font-black text-primary italic">10</span>
        </div>
        <div class="bg-white p-8 rounded-3xl border border-zinc-200 shadow-sm">
            <span class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Status</span>
            <span class="text-4xl font-black text-secondary italic uppercase tracking-tighter">Live</span>
        </div>
        <div class="bg-white p-8 rounded-3xl border border-zinc-200 shadow-sm">
            <span class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Next Match</span>
            <span class="text-lg font-black text-primary truncate block mt-2">Check Fixtures</span>
        </div>
    </div>

    <section class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-8">
        <h3 class="text-xl font-black mb-6 uppercase tracking-tight text-zinc-400">Result Entry</h3>
        <div class="space-y-4">
            @forelse($pendingMatches as $match)
            <form action="{{ route('admin.results.update', $match->id) }}" method="POST" class="flex items-center gap-6 p-6 rounded-2xl bg-zinc-50 border border-zinc-100">
                @csrf
                <div class="flex-1 text-right font-bold text-primary">{{ $match->homeTeam->name }}</div>
                <input type="number" name="home_score" class="w-16 p-3 rounded-xl border border-zinc-200 text-center font-black text-xl" required min="0">
                <span class="text-zinc-300 font-black">VS</span>
                <input type="number" name="away_score" class="w-16 p-3 rounded-xl border border-zinc-200 text-center font-black text-xl" required min="0">
                <div class="flex-1 text-left font-bold text-primary">{{ $match->awayTeam->name }}</div>
                <button type="submit" class="bg-secondary text-primary font-black px-6 py-3 rounded-xl hover:scale-105 transition">Save</button>
            </form>
            @empty
            <p class="text-center text-zinc-400 py-12 font-bold uppercase tracking-widest">No pending matches to record.</p>
            @endforelse
        </div>
    </section>

</div>
@endsection
