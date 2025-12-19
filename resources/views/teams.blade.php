@extends('layout')

@section('content')
<div class="space-y-12">
    <!-- Header -->
    <div class="text-center space-y-2">
        <h2 class="text-4xl font-black text-primary uppercase tracking-tighter">Clubs</h2>
        <span class="block text-sm font-bold text-zinc-400 uppercase tracking-widest">2025-2026 Season</span>
    </div>

    <!-- Teams Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($teams as $team)
        <a 
            href="{{ route('team.details', $team->id) }}"
            x-data="{ hover: false }" 
            @mouseenter="hover = true" 
            @mouseleave="hover = false"
            :style="hover && '{{ $team->primary_color }}' ? 'background-color: {{ $team->primary_color }}; border-color: {{ $team->primary_color }}' : ''"
            class="bg-white rounded-xl p-6 shadow-sm border border-zinc-100 hover:shadow-md transition-all duration-300 flex items-center gap-6 group cursor-pointer block"
        >
            <!-- Logo -->
            <div class="flex-shrink-0">
                @if($team->logo_url)
                <img src="{{ $team->logo_url }}" class="w-16 h-16 object-contain group-hover:scale-110 transition duration-500">
                @else
                <div class="w-16 h-16 bg-zinc-50 rounded-full flex items-center justify-center font-black text-2xl text-zinc-300 uppercase">
                    {{ substr($team->name, 0, 1) }}
                </div>
                @endif
            </div>

            <!-- Info -->
            <div class="flex-1 min-w-0">
                <h3 
                    class="font-black text-lg leading-tight mb-2 truncate transition-colors duration-300"
                    :class="hover && '{{ $team->primary_color }}' ? 'text-white' : 'text-primary'"
                >{{ $team->name }}</h3>
                <div 
                    class="flex items-center gap-2 transition-colors duration-300"
                    :class="hover && '{{ $team->primary_color }}' ? 'text-white/80' : 'text-zinc-500'"
                >
                    <img src="/images/stadium-icon.png" class="w-4 h-4 object-contain" :class="hover && '{{ $team->primary_color }}' ? 'brightness-0 invert' : 'brightness-0 opacity-40'">
                    <span class="text-xs font-bold uppercase tracking-wide truncate">{{ $team->stadium_name ?? 'Stadium TBD' }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
