@extends('admin.layout')

@section('title', 'Edit Interview')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
        <form action="{{ route('admin.interviews.update', $interview->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Interview Title</label>
                <input type="text" name="title" value="{{ $interview->title }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-black text-primary text-xl focus:ring-2 focus:ring-primary outline-none transition uppercase" required>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Interviewee Name</label>
                    <input type="text" name="interviewee_name" value="{{ $interview->interviewee_name }}" class="w-full bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Role (Optional)</label>
                    <input type="text" name="interviewee_role" value="{{ $interview->interviewee_role }}" class="w-full bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Description</label>
                <textarea name="description" rows="4" class="w-full bg-zinc-50 border border-zinc-100 p-6 rounded-2xl font-medium text-zinc-700 focus:ring-2 focus:ring-primary outline-none transition text-sm leading-relaxed">{{ $interview->description }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Video URL (Optional)</label>
                    <input type="url" name="video_url" value="{{ $interview->video_url }}" class="w-full bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Display Order</label>
                    <input type="number" name="display_order" value="{{ $interview->display_order }}" class="w-full bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Thumbnail Image (Portrait/Vertical Recommended)</label>
                <div class="flex items-center gap-4">
                    @if($interview->thumbnail_url)
                    <img src="{{ $interview->thumbnail_url }}" class="w-20 h-28 rounded-lg object-cover border border-zinc-100 shadow-sm">
                    @endif
                    <input type="file" name="thumbnail" class="flex-1 bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" accept="image/*">
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-zinc-50">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_featured" value="1" class="hidden peer" {{ $interview->is_featured ? 'checked' : '' }}>
                    <div class="w-12 h-6 bg-zinc-200 rounded-full relative transition peer-checked:bg-secondary">
                        <div class="absolute inset-y-1 left-1 w-4 h-4 bg-white rounded-full transition translate-x-0 peer-checked:translate-x-6"></div>
                    </div>
                    <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest group-hover:text-primary transition">Featured Interview</span>
                </label>

                <div class="flex gap-4">
                    <a href="{{ route('admin.interviews.index') }}" class="px-8 py-3 border border-zinc-100 rounded-xl font-black text-[10px] text-zinc-400 uppercase tracking-widest hover:bg-zinc-50 transition">Cancel</a>
                    <button type="submit" class="bg-primary text-secondary font-black px-12 py-3 rounded-xl hover:scale-105 transition uppercase tracking-widest text-[10px] shadow-lg">Update Interview</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
