@extends('admin.layout')

@section('title', 'Edit Competition')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <a href="{{ route('admin.competitions.index') }}" class="text-zinc-400 hover:text-primary transition text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-8">
        <form action="{{ route('admin.competitions.update', $competition->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 p-4 rounded-2xl">
                    <ul class="list-disc list-inside text-rose-500 text-[10px] font-black uppercase tracking-widest leading-loose">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-2">
                <label class="text-[10px] font-black text-primary uppercase tracking-widest px-1">Competition Name</label>
                <input type="text" name="name" required value="{{ $competition->name }}"
                    class="w-full bg-zinc-50 border-none rounded-2xl px-6 py-4 font-bold text-primary placeholder:text-zinc-300 focus:ring-2 focus:ring-secondary transition">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-primary uppercase tracking-widest px-1">Competition Type</label>
                    <select name="type" required
                        class="w-full bg-zinc-50 border-none rounded-2xl px-6 py-4 font-bold text-primary focus:ring-2 focus:ring-secondary transition">
                        <option value="league" {{ $competition->type == 'league' ? 'selected' : '' }}>League</option>
                        <option value="knockout" {{ $competition->type == 'knockout' ? 'selected' : '' }}>Knockout</option>
                        <option value="novelty" {{ $competition->type == 'novelty' ? 'selected' : '' }}>Novelty/Friendly</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-primary uppercase tracking-widest px-1">Status</label>
                    <div class="flex items-center h-[56px] px-6">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $competition->is_active ? 'checked' : '' }}>
                                <div class="w-10 h-6 bg-zinc-200 rounded-full shadow-inner peer-checked:bg-secondary transition-colors"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                            </div>
                            <span class="ml-3 text-[10px] font-black text-primary uppercase tracking-widest">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-primary uppercase tracking-widest px-1">Description (Optional)</label>
                <textarea name="description" rows="4"
                    class="w-full bg-zinc-50 border-none rounded-2xl px-6 py-4 font-bold text-primary placeholder:text-zinc-300 focus:ring-2 focus:ring-secondary transition">{{ $competition->description }}</textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-secondary font-black py-5 rounded-2xl hover:opacity-90 transition shadow-xl uppercase tracking-widest text-xs">
                    Update Competition
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
 Broadway  
 Broadway  
 Broadway  
 Broadway  
