@extends('layout')

@section('content')
<div class="space-y-16">
    <h2 class="text-3xl font-black text-primary flex items-center gap-3 uppercase tracking-tighter italic">
        Knockout Stage
    </h2>

    <div class="grid lg:grid-cols-2 gap-12 relative max-w-5xl mx-auto">
        <!-- Semi-Finals -->
        <section class="space-y-8">
            <h3 class="text-[11px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100 pb-2">Semi-Finals</h3>
            <div class="space-y-4">
                @foreach($semifinals as $index => $match)
                <div class="bg-white rounded-xl p-6 shadow-sm border border-zinc-100 relative group">
                    <span class="absolute -top-2.5 left-6 bg-primary text-white px-3 py-0.5 rounded text-[9px] font-black uppercase tracking-widest">SF {{ $index + 1 }}</span>
                    <div class="flex items-center justify-between gap-4 pt-2">
                        <div class="text-center flex-1">
                            <span class="block font-black text-primary text-sm">{{ $match->homeTeam->name ?? 'TBD' }}</span>
                        </div>
                        <div class="px-4">
                            @if($match->status === 'finished')
                            <div class="flex gap-1">
                                <span class="w-8 h-8 bg-primary text-white rounded flex items-center justify-center font-black text-sm">{{ $match->home_score }}</span>
                                <span class="w-8 h-8 bg-primary text-white rounded flex items-center justify-center font-black text-sm">{{ $match->away_score }}</span>
                            </div>
                            @else
                            <div class="bg-zinc-50 border border-zinc-100 px-3 py-1.5 rounded text-[10px] font-black text-zinc-400 uppercase">VS</div>
                            @endif
                        </div>
                        <div class="text-center flex-1">
                            <span class="block font-black text-primary text-sm">{{ $match->awayTeam->name ?? 'TBD' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($semifinals->count() < 2)
                <div class="bg-zinc-50 border border-dashed border-zinc-200 rounded-xl p-8 text-center text-zinc-300 font-black uppercase tracking-widest text-[10px]">
                    Waiting for Group Results
                </div>
                @endif
            </div>
        </section>

        <!-- Final -->
        <section class="space-y-8">
            <h3 class="text-[11px] font-black text-zinc-400 uppercase tracking-widest border-b border-zinc-100 pb-2">Grand Final</h3>
            @if($final)
            <div class="bg-white rounded-2xl p-10 text-center shadow-lg border border-primary/10 relative overflow-hidden group">
                <div class="absolute inset-x-0 top-0 h-1 bg-secondary"></div>
                <div class="text-secondary text-5xl mb-6">🏆</div>
                <div class="flex items-center justify-center gap-6 text-primary h-full">
                    <div class="text-base font-black uppercase tracking-tighter flex-1 text-right">{{ $final->homeTeam->name ?? 'TBD' }}</div>
                    <div class="text-3xl font-black shrink-0 px-4">
                        @if($final->status === 'finished') 
                            {{ $final->home_score }} - {{ $final->away_score }} 
                        @else 
                            <div class="bg-zinc-50 border border-zinc-100 px-4 py-2 rounded-lg text-xs font-black text-zinc-400">FINAL</div>
                        @endif
                    </div>
                    <div class="text-base font-black uppercase tracking-tighter flex-1 text-left">{{ $final->awayTeam->name ?? 'TBD' }}</div>
                </div>
            </div>
            @else
            <div class="bg-zinc-50 border border-dashed border-zinc-200 rounded-2xl p-16 text-center shadow-inner group flex flex-col items-center gap-4">
                <div class="text-zinc-200 text-6xl font-black italic uppercase tracking-tighter select-none">FINAL</div>
                <div class="text-zinc-400 font-black uppercase tracking-widest text-[9px]">Road to Champions</div>
            </div>
            @endif
        </section>
    </div>
</div>
@endsection
