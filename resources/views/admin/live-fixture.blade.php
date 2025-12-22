@extends('admin.layout')

@section('title', 'Live Match Console')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Match Info & Quick Actions -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Match Header -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-zinc-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ $match->homeTeam->logo_url ?? asset('images/default-logo.png') }}" class="w-12 h-12 object-contain">
                <div class="text-center">
                    <span class="block text-2xl font-black text-primary">{{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}</span>
                    <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Vs</span>
                </div>
                <img src="{{ $match->awayTeam->logo_url ?? asset('images/default-logo.png') }}" class="w-12 h-12 object-contain">
            </div>
            <div class="text-right">
                <span class="block text-xs font-bold text-zinc-400 uppercase">Match Status</span>
                <span class="text-sm font-black text-secondary">{{ ucfirst($match->status) }}</span>
            </div>
        </div>

        <!-- Live Entry Form -->
        <div class="bg-white rounded-3xl p-8 shadow-lg border border-primary/5 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-secondary to-primary"></div>
            <h3 class="text-lg font-black text-primary uppercase tracking-tighter mb-6">Post Update</h3>

            <form action="{{ route('admin.fixtures.live.store', $match->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-4 gap-4">
                    <div class="col-span-1">
                        <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Minute</label>
                        <input type="number" name="minute" class="w-full bg-zinc-50 border-none rounded-xl font-bold text-center focus:ring-2 focus:ring-secondary" placeholder="Min" value="{{ old('minute') }}">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Type</label>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['goal', 'foul', 'sub', 'card', 'whistle', 'var', 'info'] as $type)
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="{{ $type }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                <span class="px-3 py-1 rounded-lg bg-zinc-100 text-xs font-bold text-zinc-400 peer-checked:bg-secondary peer-checked:text-primary transition uppercase">{{ $type }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Commentary</label>
                    <textarea name="comment" rows="3" class="w-full bg-zinc-50 border-none rounded-xl font-medium focus:ring-2 focus:ring-secondary" placeholder="What's happening?"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-primary-light transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Post Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Feed History -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-zinc-100 h-[600px] overflow-y-auto custom-scrollbar">
        <h3 class="text-xs font-black text-zinc-400 uppercase tracking-widest mb-6 sticky top-0 bg-white pb-4 border-b border-zinc-50 z-10">
            Live Feed History
        </h3>

        <div class="space-y-4">
            @forelse($match->commentaries as $logs)
            <div class="group relative pl-8 pb-4 border-l-2 border-zinc-100 last:border-0 last:pb-0">
                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-4 border-zinc-200 group-hover:border-secondary transition"></div>
                
                <div class="flex items-baseline justify-between mb-1">
                    <span class="text-xs font-black text-secondary">{{ $logs->minute }}'</span>
                    <form action="{{ route('admin.fixtures.live.destroy', $logs->id) }}" method="POST" onsubmit="return confirm('Delete this entry?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[10px] text-rose-300 hover:text-rose-500 font-bold uppercase opacity-0 group-hover:opacity-100 transition">Delete</button>
                    </form>
                </div>
                
                <div class="bg-zinc-50 p-3 rounded-xl">
                    <span class="inline-block px-2 py-0.5 rounded bg-white border border-zinc-100 text-[10px] font-bold text-zinc-400 uppercase mb-1">{{ $logs->type }}</span>
                    <p class="text-sm font-medium text-zinc-600">{{ $logs->comment }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-12 h-12 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-3 text-zinc-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <p class="text-xs font-bold text-zinc-400">No commentary yet.</p>
                <p class="text-[10px] text-zinc-300 mt-1">Start typing to build the feed.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
