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

        <div class="prose prose-zinc prose-lg max-w-none text-zinc-600 font-medium leading-loose news-content">
            {!! $post->content !!}
        </div>

        <style>
            .news-content h1, .news-content h2, .news-content h3 {
                color: var(--primary);
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: -0.05em;
                margin-top: 2rem;
                margin-bottom: 1rem;
                font-style: italic;
            }
            .news-content h1 { font-size: 2.25rem; }
            .news-content h2 { font-size: 1.875rem; }
            .news-content h3 { font-size: 1.5rem; }
            .news-content p { margin-bottom: 1.5rem; }
            .news-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.5rem; }
            .news-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.5rem; }
            .news-content blockquote {
                border-left: 4px solid var(--secondary);
                padding-left: 1.5rem;
                font-style: italic;
                color: var(--primary);
                margin: 2rem 0;
            }
            .news-content .ql-video {
                width: 100%;
                aspect-ratio: 16 / 9;
                border-radius: 1.5rem;
                margin: 2rem 0;
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            }
            .news-content img {
                border-radius: 1.5rem;
                margin: 2rem 0;
            }
        </style>

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


    <!-- Comments Section -->
    <section class="pt-20 border-t border-zinc-100" id="comments" x-data="{ replyingTo: null, replyName: '' }">
        <div class="flex items-center justify-between mb-12">
            <h3 class="text-3xl font-black text-primary uppercase tracking-tighter italic">Comments ({{ $post->comments->count() + $post->comments->sum(fn($c) => $c->replies->count()) }})</h3>
            <a href="#comment-form" class="text-[10px] font-black text-secondary px-4 py-2 bg-primary rounded-lg uppercase tracking-widest hover:scale-105 transition shadow-lg">Leave a comment</a>
        </div>

        <!-- Comment Form -->
        <div class="bg-zinc-50 rounded-[2rem] p-8 md:p-12 mb-16 border border-zinc-100 shadow-sm relative overflow-hidden" id="comment-form">
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-primary/5 rounded-full blur-2xl"></div>
            
            <div class="relative z-10 space-y-8">
                <div class="space-y-2">
                    <h4 class="text-xl font-black text-primary uppercase tracking-tight italic" x-text="replyingTo ? 'Reply to ' + replyName : 'Join the conversation'">Join the conversation</h4>
                    <p class="text-xs font-medium text-zinc-400">Your email address will not be published.</p>
                </div>

                <form action="{{ route('news.comments.store', $post->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="parent_id" x-model="replyingTo">
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Name</label>
                            <input type="text" name="name" required class="w-full bg-white border border-zinc-200 px-6 py-4 rounded-2xl text-sm font-bold text-primary focus:ring-2 focus:ring-secondary outline-none transition placeholder:text-zinc-300" placeholder="e.g. John Doe">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Email <span class="text-zinc-300 italic">(Optional)</span></label>
                            <input type="email" name="email" class="w-full bg-white border border-zinc-200 px-6 py-4 rounded-2xl text-sm font-bold text-primary focus:ring-2 focus:ring-secondary outline-none transition placeholder:text-zinc-300" placeholder="john@example.com">
                        </div>
                    </div>

                    <div class="space-y-2" x-data="{ 
                        comment: '',
                        showEmojis: false,
                        insertEmoji(emoji) {
                            const textarea = this.$refs.commentArea;
                            const start = textarea.selectionStart;
                            const end = textarea.selectionEnd;
                            this.comment = this.comment.substring(0, start) + emoji + this.comment.substring(end);
                            this.showEmojis = false;
                            this.$nextTick(() => {
                                textarea.focus();
                                const newPos = start + emoji.length;
                                textarea.setSelectionRange(newPos, newPos);
                            });
                        }
                    }">
                        <div class="flex items-center justify-between ml-1">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Your Comment</label>
                            <div class="relative">
                                <button type="button" @click="showEmojis = !showEmojis" class="w-8 h-8 rounded-full bg-white border border-zinc-200 flex items-center justify-center text-lg hover:bg-zinc-50 transition shadow-sm">😊</button>
                                
                                <div x-show="showEmojis" @click.away="showEmojis = false" class="absolute bottom-10 right-0 z-50 bg-white border border-zinc-100 rounded-2xl shadow-2xl p-4 w-72 h-80 overflow-y-auto no-scrollbar grid grid-cols-6 gap-2" x-cloak>
                                    @php
                                        $emojis = ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😝', '😜', '🤪', '🤨', '🧐', '🤓', '😎', '🤩', '🥳', '😏', '😒', '😞', '😔', '😟', '😕', '🙁', '☹️', '😮', '😯', '😲', '😳', '🥺', '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣', '😞', '😓', '😩', '😫', '🥱', '😤', '😡', '😠', '🤬', '😈', '👿', '💀', '☠️', '💩', '🤡', '👹', '👺', '👻', '👽', '👾', '🤖', '🎃', '😺', '😸', '😹', '😻', '😼', '😽', '🙀', '😿', '😾', '👋', '🤚', '🖐', '✋', '🖖', '👌', '🤏', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆', '🖕', '👇', '☝️', '👍', '👎', '✊', '👊', '🤛', '🤜', '👏', '🙌', '👐', '🤲', '🤝', '🙏', '✍️', '💅', '🤳', '💪', '🦾', '🦵', '🦿', '🦶', '👂', '🦻', '👃', '🧠', '🦷', '🦴', '👀', '👁', '👅', '👄', '💋', '🩸', '⚽', '🏆', '🔥', '💎', '⭐'];
                                    @endphp
                                    @foreach($emojis as $emoji)
                                        <button type="button" @click="insertEmoji('{{ $emoji }}')" class="hover:bg-zinc-50 p-1.5 rounded-lg transition text-xl flex items-center justify-center">{{ $emoji }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <textarea x-ref="commentArea" x-model="comment" name="comment" required rows="5" class="w-full bg-white border border-zinc-200 px-6 py-4 rounded-2xl text-sm font-bold text-primary focus:ring-2 focus:ring-secondary outline-none transition placeholder:text-zinc-300 resize-none" placeholder="What's your thought?"></textarea>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="flex-1 py-4 bg-primary text-secondary text-xs font-black uppercase tracking-widest rounded-2xl hover:scale-[1.02] active:scale-95 transition shadow-lg">Post Comment</button>
                        <button type="button" x-show="replyingTo" @click="replyingTo = null" class="px-6 py-4 bg-zinc-200 text-zinc-600 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-zinc-300 transition">Cancel Reply</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-12 p-6 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-3xl flex items-center gap-4 animate-fade-in-up">
            <span class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg">✓</span>
            <p class="text-sm font-bold uppercase tracking-tight">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Comments List -->
        <div class="space-y-8">
            @forelse($post->comments as $comment)
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-zinc-100 shadow-sm space-y-4 group">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-zinc-100 rounded-2xl flex items-center justify-center font-black text-zinc-400 text-sm">
                                {{ substr($comment->name, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="text-sm font-black text-primary uppercase tracking-tight">{{ $comment->name }}</h5>
                                <span class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest italic">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <button @click="replyingTo = {{ $comment->id }}; replyName = '{{ $comment->name }}'; window.scrollTo({top: document.getElementById('comment-form').offsetTop - 100, behavior: 'smooth'})" class="text-[9px] font-black text-zinc-400 uppercase tracking-widest border border-zinc-100 px-4 py-2 rounded-lg hover:bg-primary hover:text-secondary transition shadow-sm">Reply</button>
                    </div>
                    <div class="text-sm font-medium text-zinc-600 leading-relaxed pl-16">
                        {{ $comment->comment }}
                    </div>

                    <!-- Threaded Replies -->
                    @if($comment->replies->count() > 0)
                    <div class="pl-16 space-y-6 pt-4">
                        @foreach($comment->replies as $reply)
                        <div class="bg-zinc-50 rounded-2xl p-4 md:p-6 border border-zinc-100/50 space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-zinc-200 rounded-xl flex items-center justify-center font-black text-zinc-400 text-[10px]">
                                    {{ substr($reply->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="text-xs font-black text-primary uppercase tracking-tight">{{ $reply->name }}</h6>
                                    <span class="text-[9px] font-bold text-zinc-300 uppercase tracking-widest italic">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-xs font-medium text-zinc-500 leading-relaxed">
                                {{ $reply->comment }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @empty
                <div class="bg-zinc-50 rounded-[2rem] p-16 text-center border border-zinc-100 border-dashed">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-zinc-300 text-2xl animate-bounce shadow-sm">💬</div>
                    <h4 class="text-sm font-black text-zinc-400 uppercase tracking-widest mb-1">No comments yet</h4>
                    <p class="text-[10px] text-zinc-300 italic">Be the first to share your thoughts!</p>
                </div>
            @endforelse
        </div>
    </section>

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
