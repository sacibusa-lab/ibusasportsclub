@extends('layout')

@section('content')
<div class="space-y-12">
    <div class="flex items-center justify-between border-b border-zinc-100 pb-8">
        <h2 class="text-4xl font-black text-primary uppercase tracking-tighter italic">Latest News</h2>
        <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
            <a href="{{ route('news.index') }}" class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition {{ !request('category') ? 'bg-primary text-secondary shadow-lg' : 'bg-white text-zinc-400 border border-zinc-100' }}">All</a>
            @foreach($categories as $cat)
            <a href="{{ route('news.index', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition {{ request('category') == $cat->slug ? 'bg-primary text-secondary shadow-lg' : 'bg-white text-zinc-400 border border-zinc-100' }}">{{ $cat->name }}</a>
            @endforeach
        </div>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post)
        <article class="bg-white rounded-2xl overflow-hidden shadow-sm border border-zinc-100 hover:shadow-xl transition-all group flex flex-col">
            <div class="aspect-video relative overflow-hidden bg-zinc-100">
                @if($post->image_url)
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                @endif
                <div class="absolute top-4 left-4">
                    <span class="bg-primary text-secondary text-[9px] font-black px-3 py-1 rounded uppercase tracking-widest shadow-lg">{{ $post->category->name }}</span>
                </div>
            </div>
            <div class="p-8 flex-1 flex flex-col">
                <div class="text-[9px] font-black text-zinc-300 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <span>{{ $post->published_at ? $post->published_at->format('j F Y') : $post->created_at->format('j F Y') }}</span>
                    <span>•</span>
                    <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} Min Read</span>
                </div>
                <h3 class="text-xl font-black text-primary mb-4 leading-tight group-hover:text-secondary transition uppercase tracking-tighter line-clamp-2">
                    <a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a>
                </h3>
                <p class="text-zinc-500 text-sm leading-relaxed line-clamp-3 mb-6 font-medium">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                <div class="mt-auto pt-6 border-t border-zinc-50 flex items-center justify-between">
                    <div class="flex flex-wrap gap-1">
                        @foreach($post->tags->take(2) as $tag)
                        <span class="text-[8px] font-black text-zinc-300 uppercase">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <a href="{{ route('news.show', $post->slug) }}" class="text-primary hover:text-secondary transition text-xs font-black italic uppercase tracking-widest">Read More →</a>
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-full py-20 text-center">
            <p class="text-zinc-300 font-black uppercase tracking-widest text-sm italic">No articles found in this section.</p>
        </div>
        @endforelse
    </div>

    <div class="pt-12">
        {{ $posts->links() }}
    </div>
</div>
@endsection
