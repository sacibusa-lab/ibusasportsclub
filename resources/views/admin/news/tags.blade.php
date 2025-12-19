@extends('admin.layout')

@section('title', 'Manage Tags')

@section('content')
<div class="grid lg:grid-cols-3 gap-12">
    <!-- Tag Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6 border-b border-zinc-50 pb-4">Add New Tag</h3>
            <form action="{{ route('admin.news.tags.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Tag Name</label>
                    <input type="text" name="name" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" placeholder="e.g. Community" required>
                </div>
                <button type="submit" class="w-full bg-primary text-secondary font-black py-4 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-[10px] shadow-lg">Create Tag</button>
            </form>
        </div>
    </div>

    <!-- Tag Table -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-zinc-50 text-zinc-400 text-[10px] font-black uppercase tracking-widest border-b border-zinc-100">
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Posts</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @foreach($tags as $tag)
                    <tr class="hover:bg-zinc-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-primary text-sm uppercase tracking-tighter">{{ $tag->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-zinc-400 italic">
                            {{ $tag->posts_count }} Articles
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.news.tags.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Delete tag?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-zinc-300 hover:text-accent transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
