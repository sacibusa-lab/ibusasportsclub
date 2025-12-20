@extends('admin.layout')

@section('title', 'Manage Stories')

@section('content')
<div class="grid lg:grid-cols-3 gap-12">
    <!-- Add Story Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6 border-b border-zinc-50 pb-4">Add New Story</h3>
            <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Story Title</label>
                    <input type="text" name="title" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" placeholder="e.g. AFC Final Highlights" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">External Link (Redirects on tap)</label>
                    <input type="url" name="link_url" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" placeholder="https://blogspot.com/...">
                </div>
                <div class="space-y-6" x-data="{ items: [{id: 1, fileName: ''}] }">
                    <div class="space-y-4">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Story Items (Images/Videos)</label>
                        <template x-for="(item, index) in items" :key="item.id">
                            <div class="flex items-center gap-3 animate-fade-in">
                                <div class="flex-1 relative group">
                                    <input type="file" name="media[]" @change="item.fileName = $event.target.files[0]?.name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*,video/mp4" required>
                                    <div class="w-full bg-zinc-50 border border-zinc-100 p-3 rounded-xl flex items-center gap-3 transition group-hover:border-primary">
                                        <div class="w-8 h-8 bg-zinc-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-zinc-300" :class="item.fileName ? 'text-primary' : 'text-zinc-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <span class="text-[10px] font-bold uppercase truncate" :class="item.fileName ? 'text-primary' : 'text-zinc-400'" x-text="item.fileName || 'Choose File...'"></span>
                                    </div>
                                </div>
                                <button type="button" @click="items = items.filter(i => i.id !== item.id)" x-show="items.length > 1" class="p-2 text-zinc-300 hover:text-rose-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="items.push({id: Date.now(), fileName: ''})" class="w-full py-3 border-2 border-dashed border-zinc-100 rounded-xl text-zinc-300 hover:text-primary hover:border-primary transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-[9px] font-black uppercase tracking-widest">Add another item</span>
                        </button>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Story Duration</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer group">
                                <input type="radio" name="duration" value="24h" class="hidden peer" checked>
                                <div class="p-4 bg-zinc-50 border border-zinc-100 rounded-2xl text-center peer-checked:bg-primary peer-checked:text-secondary transition group-hover:bg-zinc-100 peer-checked:group-hover:bg-primary">
                                    <span class="text-[10px] font-black uppercase tracking-widest">24 Hours</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="duration" value="permanent" class="hidden peer">
                                <div class="p-4 bg-zinc-50 border border-zinc-100 rounded-2xl text-center peer-checked:bg-primary peer-checked:text-secondary transition group-hover:bg-zinc-100 peer-checked:group-hover:bg-primary">
                                    <span class="text-[10px] font-black uppercase tracking-widest">Permanent</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Common Media Type</label>
                        <select name="type" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                </div>
                <!-- Link URL removed from main form for now as it's per item in more advanced setups, but keeping it simple -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" checked class="w-4 h-4 text-primary border-zinc-300 rounded focus:ring-primary">
                    <label for="is_active" class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Active</label>
                </div>
                <button type="submit" class="w-full bg-primary text-secondary font-black py-4 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-[10px] shadow-lg">Upload Story Group</button>
            </form>
        </div>
    </div>

    <!-- Stories List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-zinc-50 text-zinc-400 text-[10px] font-black uppercase tracking-widest border-b border-zinc-100">
                        <th class="px-6 py-4">Preview</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @foreach($stories as $story)
                    <tr class="hover:bg-zinc-50 transition">
                        <td class="px-6 py-4">
                            <div class="w-12 h-16 bg-zinc-100 rounded-lg overflow-hidden flex items-center justify-center border border-zinc-100 shadow-sm relative">
                                <img src="{{ $story->thumbnail_url ?? ($story->items->first()->media_url ?? '') }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-primary text-sm uppercase tracking-tighter">{{ $story->title }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black text-zinc-500 bg-zinc-100 px-2 py-1 rounded">{{ $story->items->count() }} slides</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($story->is_active)
                            <span class="text-[8px] font-black px-2 py-1 rounded bg-[#00ff85]/10 text-[#006635] uppercase tracking-widest">Active</span>
                            @else
                            <span class="text-[8px] font-black px-2 py-1 rounded bg-zinc-100 text-zinc-400 uppercase tracking-widest">Inactive</span>
                            @endif
                            <div class="mt-1">
                                @if($story->expires_at)
                                <span class="text-[7px] font-bold text-zinc-400 uppercase tracking-tighter">Expires: {{ $story->expires_at->diffForHumans() }}</span>
                                @else
                                <span class="text-[7px] font-bold text-secondary uppercase tracking-tighter bg-primary px-1 rounded">Permanent</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.stories.edit', $story->id) }}" class="p-2 text-zinc-300 hover:text-primary transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <form action="{{ route('admin.stories.destroy', $story->id) }}" method="POST" onsubmit="return confirm('Delete this story?')">
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
</div>
@endsection
