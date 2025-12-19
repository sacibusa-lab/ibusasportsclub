@extends('admin.layout')

@section('title', 'All Posts')

@section('content')
<div class="space-y-8">
    <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-zinc-50 text-zinc-400 text-[10px] font-black uppercase tracking-widest border-b border-zinc-100">
                    <th class="px-6 py-4">Article</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @foreach($posts as $post)
                <tr class="hover:bg-zinc-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            @if($post->image_url)
                            <img src="{{ $post->image_url }}" class="w-12 h-12 rounded-lg object-cover bg-zinc-100">
                            @else
                            <div class="w-12 h-12 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                            <div class="flex flex-col">
                                <span class="font-extrabold text-primary text-sm uppercase tracking-tighter">{{ $post->title }}</span>
                                <span class="text-zinc-400 font-bold text-[9px] uppercase tracking-widest">{{ Str::limit(strip_tags($post->content), 40) }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[9px] font-black px-2 py-1 rounded bg-zinc-100 text-zinc-500 uppercase tracking-widest">{{ $post->category->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block w-2 h-2 rounded-full {{ $post->is_published ? 'bg-secondary' : 'bg-zinc-200' }} mr-1"></span>
                        <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">{{ $post->is_published ? 'Published' : 'Draft' }}</span>
                    </td>
                    <td class="px-6 py-4 text-zinc-500 text-xs font-bold uppercase">{{ $post->created_at->format('D j M') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.news.edit', $post->id) }}" class="p-2 text-zinc-300 hover:text-primary transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.news.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-zinc-300 hover:text-accent transition">
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
</div>
@endsection
