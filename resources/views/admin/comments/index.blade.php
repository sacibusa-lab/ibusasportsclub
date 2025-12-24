@extends('admin.layout')

@section('title', 'Manage Comments')

@section('content')
<div class="space-y-8">
    <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-zinc-50 text-zinc-400 text-[10px] font-black uppercase tracking-widest border-b border-zinc-100">
                    <th class="px-6 py-4">Author</th>
                    <th class="px-6 py-4">Comment</th>
                    <th class="px-6 py-4">Post</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @foreach($comments as $comment)
                <tr class="hover:bg-zinc-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-extrabold text-primary text-sm uppercase tracking-tighter">{{ $comment->name }}</span>
                            <span class="text-zinc-400 font-bold text-[9px] uppercase tracking-widest">{{ $comment->email ?? 'Guest' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        <p class="text-zinc-500 font-bold text-[10px] uppercase tracking-tight line-clamp-2 italic">{{ $comment->comment }}</p>
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-zinc-400 uppercase tracking-tighter">
                        @if($comment->post)
                        <a href="{{ route('news.show', $comment->post->slug) }}" target="_blank" class="hover:text-primary transition underline decoration-zinc-100">{{ Str::limit($comment->post->title, 30) }}</a>
                        @else
                        <span class="text-zinc-300 italic">Deleted Post</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.comments.toggle', $comment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 group">
                                <span class="inline-block w-2 h-2 rounded-full {{ $comment->is_approved ? 'bg-secondary' : 'bg-red-400' }} group-hover:scale-150 transition"></span>
                                <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">{{ $comment->is_approved ? 'Approved' : 'Pending' }}</span>
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-zinc-500 text-[10px] font-black uppercase italic">{{ $comment->created_at->format('D j M, H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-zinc-200 hover:text-accent transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $comments->links() }}
    </div>
</div>
@endsection
