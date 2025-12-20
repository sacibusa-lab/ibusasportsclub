@extends('admin.layout')

@section('title', 'Edit Story')

@section('content')
<div class="max-w-4xl">
    <div class="mb-8">
        <a href="{{ route('admin.stories.index') }}" class="text-xs font-black text-zinc-400 hover:text-primary transition flex items-center gap-2 uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back to Stories
        </a>
    </div>

    <form action="{{ route('admin.stories.update', $story->id) }}" method="POST" enctype="multipart/form-data" class="space-y-12">
        @csrf
        @method('PUT')

        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Sidebar: Details -->
            <div class="space-y-8">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
                    <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6 border-b border-zinc-50 pb-4">Story Group Details</h3>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Story Title</label>
                            <input type="text" name="title" value="{{ $story->title }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">External Link</label>
                            <input type="url" name="link_url" value="{{ $story->link_url }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" placeholder="https://blogspot.com/...">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Story Duration</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="duration" value="24h" class="hidden peer" {{ $story->expires_at ? 'checked' : '' }}>
                                    <div class="p-4 bg-zinc-50 border border-zinc-100 rounded-2xl text-center peer-checked:bg-primary peer-checked:text-secondary transition group-hover:bg-zinc-100 peer-checked:group-hover:bg-primary">
                                        <span class="text-[10px] font-black uppercase tracking-widest">24 Hours</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="duration" value="permanent" class="hidden peer" {{ !$story->expires_at ? 'checked' : '' }}>
                                    <div class="p-4 bg-zinc-50 border border-zinc-100 rounded-2xl text-center peer-checked:bg-primary peer-checked:text-secondary transition group-hover:bg-zinc-100 peer-checked:group-hover:bg-primary">
                                        <span class="text-[10px] font-black uppercase tracking-widest">Permanent</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="is_active" {{ $story->is_active ? 'checked' : '' }} class="w-4 h-4 text-primary border-zinc-300 rounded focus:ring-primary">
                            <label for="is_active" class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Group is Active</label>
                        </div>
                    </div>
                </div>

                <div class="bg-primary text-secondary rounded-3xl p-8 shadow-lg">
                    <h3 class="text-xs font-black uppercase tracking-widest mb-4">Add More Slides</h3>
                    <div class="space-y-4" x-data="{ items: [] }">
                        <div class="space-y-3">
                            <template x-for="(item, index) in items" :key="item.id">
                                <div class="flex flex-col gap-1 p-3 bg-white/10 rounded-xl relative group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2 max-w-[80%] overflow-hidden">
                                            <svg class="w-4 h-4 text-white/40 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span class="text-[9px] font-bold text-white truncate" x-text="item.fileName || 'Click to choose file...'"></span>
                                        </div>
                                        <button type="button" @click="items = items.filter(i => i.id !== item.id)" class="text-white/40 hover:text-white transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <input type="file" name="media[]" @change="item.fileName = $event.target.files[0]?.name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="items.push({id: Date.now(), fileName: ''})" class="w-full py-3 border-2 border-dashed border-white/20 rounded-2xl text-white/50 hover:text-white hover:border-white/40 transition text-[10px] font-black uppercase tracking-widest">
                            + Add Another Slide
                        </button>
                        
                        <div class="space-y-2 pt-4">
                            <label class="text-[9px] font-black text-white/40 uppercase tracking-widest">Type for new items</label>
                            <select name="type" class="w-full bg-white/10 border border-white/10 p-3 rounded-xl font-bold text-white outline-none transition uppercase text-[10px]" required>
                                <option value="image" class="text-primary">Image</option>
                                <option value="video" class="text-primary">Video</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-accent text-primary font-black py-5 rounded-3xl hover:bg-accent/90 transition uppercase tracking-widest text-xs shadow-xl">
                    Save Changes
                </button>
            </div>

            <!-- Main Content: Slides -->
            <div class="space-y-6">
                <h3 class="text-xs font-black text-primary uppercase tracking-widest">Current Slides ({{ $story->items->count() }})</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($story->items as $item)
                    <div class="group relative aspect-[9/16] bg-zinc-100 rounded-2xl overflow-hidden border border-zinc-100 shadow-sm">
                        @if($item->type === 'image')
                        <img src="{{ $item->media_url }}" class="w-full h-full object-cover">
                        @else
                        <video src="{{ $item->media_url }}" class="w-full h-full object-cover"></video>
                        @endif
                        
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center p-4">
                            <form action="{{ route('admin.stories.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this slide?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-500 text-white p-3 rounded-full hover:scale-110 transition shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                        
                        <div class="absolute top-2 left-2 px-2 py-0.5 bg-black/50 backdrop-blur-md rounded text-[8px] font-black text-white uppercase tracking-widest">
                            #{{ $loop->iteration }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
