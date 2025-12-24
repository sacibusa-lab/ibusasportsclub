@extends('layout')

@section('title', $interview->title)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-600 hover:text-primary transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <!-- Interview Header -->
    <div class="bg-white rounded-3xl shadow-lg border border-zinc-100 overflow-hidden mb-8">
        <div class="p-8 md:p-12">
            <div class="flex items-start justify-between gap-6 mb-6">
                <div class="flex-1">
                    <h1 class="text-4xl md:text-5xl font-black text-primary uppercase tracking-tight leading-tight mb-4">{{ $interview->title }}</h1>
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-xl font-black text-zinc-700">{{ $interview->interviewee_name }}</p>
                            @if($interview->interviewee_role)
                            <p class="text-sm font-bold text-zinc-400 uppercase tracking-wide">{{ $interview->interviewee_role }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @if($interview->is_featured)
                <span class="bg-[#6d28d9] text-white text-xs font-black px-4 py-2 rounded-full uppercase tracking-widest shrink-0">Featured</span>
                @endif
            </div>

            @if($interview->description)
            <div class="prose prose-lg max-w-none">
                <p class="text-zinc-600 leading-relaxed">{{ $interview->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Video Player -->
    @if($interview->video_url)
    <div class="bg-white rounded-3xl shadow-lg border border-zinc-100 overflow-hidden mb-8">
        <div class="aspect-video bg-zinc-900 relative">
            @php
                $videoUrl = $interview->video_url;
                $isYoutube = str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be');
                $isVimeo = str_contains($videoUrl, 'vimeo.com');
                
                // Extract YouTube video ID
                if ($isYoutube) {
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches);
                    $youtubeId = $matches[1] ?? null;
                }
                
                // Extract Vimeo video ID
                if ($isVimeo) {
                    preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches);
                    $vimeoId = $matches[1] ?? null;
                }
            @endphp

            @if($isYoutube && isset($youtubeId))
                <!-- YouTube Embed -->
                <iframe 
                    class="w-full h-full" 
                    src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=1&rel=0" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            @elseif($isVimeo && isset($vimeoId))
                <!-- Vimeo Embed -->
                <iframe 
                    class="w-full h-full" 
                    src="https://player.vimeo.com/video/{{ $vimeoId }}?autoplay=1&muted=1" 
                    frameborder="0" 
                    allow="autoplay; fullscreen; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            @else
                <!-- HTML5 Video Player for uploaded videos -->
                <video class="w-full h-full" controls>
                    <source src="{{ $videoUrl }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @endif
        </div>
    </div>
    @else
    <!-- No Video Available -->
    <div class="bg-white rounded-3xl shadow-lg border border-zinc-100 overflow-hidden mb-8">
        <div class="aspect-video bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
            <div class="text-center">
                <svg class="w-24 h-24 mx-auto text-white/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <p class="text-white/60 font-bold uppercase tracking-widest text-sm">Video Coming Soon</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Additional Info -->
    <div class="bg-zinc-50 rounded-2xl p-6 border border-zinc-100">
        <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Published {{ $interview->created_at->diffForHumans() }}</p>
    </div>
</div>
@endsection
