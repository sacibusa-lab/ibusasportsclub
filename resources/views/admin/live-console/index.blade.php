@extends('admin.layout')

@section('title', 'Live Match Selection')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($matches as $match)
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100 flex flex-col items-center text-center space-y-6 relative overflow-hidden">
            @if($match->status === 'live' || $match->started_at)
            <div class="absolute top-4 right-4 flex items-center gap-2 px-3 py-1 bg-rose-500 text-white text-[10px] font-black rounded-full uppercase tracking-widest animate-pulse">
                <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                LIVE
            </div>
            @endif

            <div class="flex items-center gap-8 w-full justify-center">
                <div class="flex flex-col items-center space-y-3 flex-1">
                    <img src="{{ $match->homeTeam->logo_url }}" class="w-20 h-20 object-contain shadow-sm rounded-2xl p-2 bg-zinc-50 border border-zinc-100">
                    <span class="text-xs font-black text-primary uppercase">{{ $match->homeTeam->name }}</span>
                </div>
                
                <div class="flex flex-col items-center">
                    @if($match->status === 'live')
                    <div class="text-4xl font-black text-primary flex items-center gap-3">
                        <span>{{ $match->home_score ?? 0 }}</span>
                        <span class="text-zinc-200">-</span>
                        <span>{{ $match->away_score ?? 0 }}</span>
                    </div>
                    @else
                    <div class="text-xs font-black text-zinc-400 uppercase tracking-widest bg-zinc-50 px-4 py-2 rounded-xl">VS</div>
                    @endif
                    <span class="text-[10px] font-bold text-zinc-400 mt-2 uppercase tracking-tight">{{ $match->match_date->format('d M, H:i') }}</span>
                </div>

                <div class="flex flex-col items-center space-y-3 flex-1">
                    <img src="{{ $match->awayTeam->logo_url }}" class="w-20 h-20 object-contain shadow-sm rounded-2xl p-2 bg-zinc-50 border border-zinc-100">
                    <span class="text-xs font-black text-primary uppercase">{{ $match->awayTeam->name }}</span>
                </div>
            </div>

            <div class="pt-6 w-full border-t border-zinc-50">
                <a href="{{ route('admin.live-console.control', $match->id) }}" class="block w-full bg-primary text-secondary font-black py-4 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-xs shadow-lg">
                    Control Live Console
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if($matches->isEmpty())
    <div class="bg-white rounded-3xl p-12 text-center border border-zinc-100 shadow-sm">
        <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-6 text-zinc-300">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-lg font-black text-primary uppercase mb-2">No Upcoming Matches</h3>
        <p class="text-zinc-400 text-sm font-bold uppercase tracking-widest">Matches will appear here as their date approaches.</p>
        <div class="mt-8">
            <a href="{{ route('admin.fixtures') }}" class="text-primary font-black text-[10px] uppercase border-b-2 border-primary pb-1">Create Fixture</a>
        </div>
    </div>
    @endif
</div>
@endsection
