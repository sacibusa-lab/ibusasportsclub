@extends('layout')

@section('title', 'Tournament Gallery')

@section('content')
<div class="max-w-6xl mx-auto space-y-12 pb-24 px-4 md:px-0">
    <!-- Header -->
    <div class="text-center space-y-4">
        <h2 class="text-[10px] md:text-xs font-black text-primary uppercase tracking-[0.4em]">Cinematic Moments</h2>
        <h1 class="text-3xl md:text-5xl font-black text-primary italic tracking-tighter uppercase">Tournament Gallery</h1>
        <div class="h-1.5 w-24 bg-secondary mx-auto rounded-full shadow-lg shadow-secondary/20"></div>
    </div>

    @forelse($matchesWithImages as $match)
    <div class="space-y-6">
        <!-- Match Header -->
        <div class="flex items-center justify-between border-b border-zinc-100 pb-4">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <img src="{{ $match->homeTeam->logo_url }}" class="w-6 h-6 object-contain">
                    <span class="text-[10px] font-black text-primary uppercase">{{ $match->homeTeam->short_name ?? $match->homeTeam->name }}</span>
                </div>
                <span class="text-[10px] font-black text-zinc-300">VS</span>
                <div class="flex items-center gap-2">
                    <img src="{{ $match->awayTeam->logo_url }}" class="w-6 h-6 object-contain">
                    <span class="text-[10px] font-black text-primary uppercase">{{ $match->awayTeam->short_name ?? $match->awayTeam->name }}</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($match->match_date)->format('d F Y') }}</p>
                <a href="{{ route('match.details', $match->id) }}" class="text-[8px] font-black text-secondary hover:text-primary transition uppercase tracking-widest">View Match Report →</a>
            </div>
        </div>

        <!-- Image Gallery Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($match->images as $image)
            <div x-data="{ open: false }">
                <div @click="open = true" class="group relative aspect-[4/3] rounded-2xl md:rounded-3xl overflow-hidden border border-zinc-100 shadow-sm cursor-pointer hover:shadow-xl transition-all duration-500 bg-zinc-50">
                    <img src="{{ $image->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $image->caption }}">
                    
                    @if($image->caption)
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-4 md:p-6">
                        <p class="text-[10px] md:text-xs font-bold text-white leading-tight translate-y-2 group-hover:translate-y-0 transition-transform duration-500">{{ $image->caption }}</p>
                    </div>
                    @endif
                    
                    <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-500">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Lightbox (Alpine.js) -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-12 bg-primary/95 backdrop-blur-xl" 
                     @click.away="open = false" 
                     x-cloak>
                    
                    <button @click="open = false" class="absolute top-8 right-8 text-secondary/60 hover:text-secondary transition text-4xl font-light">&times;</button>
                    
                    <div class="max-w-5xl w-full space-y-6">
                        <img src="{{ $image->image_url }}" class="w-full h-auto max-h-[80vh] object-contain rounded-2xl md:rounded-[2rem] shadow-2xl border-4 border-white/10" alt="{{ $image->caption }}">
                        
                        @if($image->caption)
                        <div class="text-center space-y-2">
                             <div class="h-1 w-12 bg-secondary mx-auto rounded-full"></div>
                             <p class="text-lg md:text-2xl font-black text-white italic tracking-tighter uppercase">{{ $image->caption }}</p>
                             <div class="flex items-center justify-center gap-4 text-secondary/40 text-[10px] font-black uppercase tracking-[0.2em]">
                                 <span>{{ $match->homeTeam->name }}</span>
                                 <span>VS</span>
                                 <span>{{ $match->awayTeam->name }}</span>
                             </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="py-24 text-center space-y-6 bg-white rounded-[3rem] border-2 border-dashed border-zinc-100">
        <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-10 h-10 text-zinc-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <p class="text-xs font-black text-zinc-300 uppercase tracking-widest">No gallery images available yet.</p>
    </div>
    @endforelse
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
