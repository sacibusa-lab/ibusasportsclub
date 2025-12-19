@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto space-y-12">
    <!-- Breadcrumbs -->
    <nav class="flex text-[10px] font-bold text-zinc-400 uppercase tracking-widest gap-2 italic">
        <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
        <span>/</span>
        <a href="{{ route('news.index') }}" class="hover:text-primary transition">News</a>
        <span>/</span>
        <span class="text-primary">{{ $post->category->name }}</span>
    </nav>

    <article class="space-y-12">
        <header class="space-y-6 text-center">
            <span class="bg-primary text-secondary text-[10px] font-black px-4 py-1.5 rounded uppercase tracking-widest shadow-lg inline-block">{{ $post->category->name }}</span>
            <h1 class="text-4xl md:text-6xl font-black text-primary leading-[1.1] uppercase tracking-tighter italic">{{ $post->title }}</h1>
            <div class="text-[11px] font-black text-zinc-400 uppercase tracking-widest flex items-center justify-center gap-4 border-y border-zinc-100 py-4">
                <span>By LC News Team</span>
                <span>•</span>
                <span>{{ $post->published_at ? $post->published_at->format('j F Y') : $post->created_at->format('j F Y') }}</span>
                <span>•</span>
                <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} Min Read</span>
            </div>
        </header>

        @if($post->image_url)
        <div class="rounded-3xl overflow-hidden shadow-2xl bg-zinc-100 aspect-video">
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        </div>
        @endif

        <div class="prose prose-zinc prose-lg max-w-none text-zinc-600 font-medium leading-loose">
            {!! nl2br(e($post->content)) !!}
        </div>

        @if($post->match)
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-zinc-100 space-y-6 relative overflow-hidden group">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition duration-700"></div>
            
            <div class="flex items-center justify-between border-b border-zinc-50 pb-4">
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.3em]">Related Match Report</span>
                <span class="text-[9px] font-black text-white bg-primary px-3 py-1 rounded-full uppercase tracking-widest">{{ $post->match->stage }}</span>
            </div>

            <div class="flex items-center justify-between gap-6">
                <div class="flex flex-col items-center gap-2 flex-1">
                    @if($post->match->homeTeam->logo_url)
                    <img src="{{ $post->match->homeTeam->logo_url }}" class="w-16 h-16 object-contain">
                    @endif
                    <span class="text-xs font-black text-primary uppercase text-center">{{ $post->match->homeTeam->name }}</span>
                </div>

                <div class="flex flex-col items-center gap-1">
                    @if($post->match->status === 'finished')
                    <div class="text-3xl font-black text-primary italic">
                        {{ $post->match->home_score }} - {{ $post->match->away_score }}
                    </div>
                    @else
                    <div class="text-xl font-black text-zinc-300 italic uppercase italic">Vs</div>
                    @endif
                    <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">{{ $post->match->match_date->format('j F Y') }}</span>
                </div>

                <div class="flex flex-col items-center gap-2 flex-1">
                    @if($post->match->awayTeam->logo_url)
                    <img src="{{ $post->match->awayTeam->logo_url }}" class="w-16 h-16 object-contain">
                    @endif
                    <span class="text-xs font-black text-primary uppercase text-center">{{ $post->match->awayTeam->name }}</span>
                </div>
            </div>

            <a href="{{ route('match.details', $post->match_id) }}" class="flex items-center justify-center gap-2 w-full py-4 bg-zinc-50 rounded-2xl text-[10px] font-black text-primary uppercase tracking-widest hover:bg-primary hover:text-secondary hover:scale-[1.02] transition shadow-sm">
                View Full Match Center & Stats →
            </a>
        </div>
        @endif

        <footer class="pt-12 border-t border-zinc-100">
            <div class="flex flex-wrap gap-3 items-center">
                <span class="text-[10px] font-black text-zinc-300 uppercase tracking-widest mr-2">Tags:</span>
                @foreach($post->tags as $tag)
                <span class="px-4 py-2 bg-zinc-50 rounded-full text-[10px] font-black uppercase tracking-widest text-zinc-400 border border-zinc-100">#{{ $tag->name }}</span>
                @endforeach
            </div>
        </footer>
    </article>

    <!-- Related News -->
    @if($relatedPosts->count() > 0)
    <section class="pt-20 space-y-8">
        <h3 class="text-2xl font-black text-primary uppercase tracking-tighter italic border-b border-zinc-100 pb-4">More from {{ $post->category->name }}</h3>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($relatedPosts as $related)
            <a href="{{ route('news.show', $related->slug) }}" class="group space-y-4 block">
                <div class="aspect-video rounded-xl overflow-hidden bg-zinc-100 shadow-sm">
                    @if($related->image_url)
                    <img src="{{ $related->image_url }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    @endif
                </div>
                <h4 class="text-sm font-black text-primary uppercase leading-tight line-clamp-2 group-hover:text-secondary transition">{{ $related->title }}</h4>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
