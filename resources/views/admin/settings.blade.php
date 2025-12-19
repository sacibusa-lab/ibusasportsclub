@extends('admin.layout')

@section('title', 'Site Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-12">
    <div>
        <h2 class="text-3xl font-black text-primary uppercase italic tracking-tighter">Global Settings</h2>
        <p class="text-zinc-400 text-xs font-bold uppercase tracking-widest mt-1">Configure your tournament brand and global variables</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- General Settings -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-8 border-b border-zinc-50 pb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                General Information
            </h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Tournament Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Short Name/Abbreviation</label>
                    <input type="text" name="site_short_name" value="{{ $settings['site_short_name'] }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Current Season</label>
                    <input type="text" name="current_season" value="{{ $settings['current_season'] }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                </div>
            </div>
        </div>

        <!-- Branding & Colors -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-8 border-b border-zinc-50 pb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.828 2.828a2 2 0 010 2.828l-8.486 8.486L11 21M7 17v.01"/></svg>
                Branding & Visuals
            </h3>
            <div class="space-y-8">
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Primary Color</label>
                        <div class="flex gap-2">
                            <input type="color" name="primary_color" value="{{ $settings['primary_color'] }}" class="h-12 w-12 rounded-xl cursor-pointer bg-zinc-50 border border-zinc-100 p-1">
                            <input type="text" value="{{ $settings['primary_color'] }}" class="flex-1 bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-mono text-xs text-primary" readonly>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Secondary Color</label>
                        <div class="flex gap-2">
                            <input type="color" name="secondary_color" value="{{ $settings['secondary_color'] }}" class="h-12 w-12 rounded-xl cursor-pointer bg-zinc-50 border border-zinc-100 p-1">
                            <input type="text" value="{{ $settings['secondary_color'] }}" class="flex-1 bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-mono text-xs text-primary" readonly>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Accent Color</label>
                        <div class="flex gap-2">
                            <input type="color" name="accent_color" value="{{ $settings['accent_color'] }}" class="h-12 w-12 rounded-xl cursor-pointer bg-zinc-50 border border-zinc-100 p-1">
                            <input type="text" value="{{ $settings['accent_color'] }}" class="flex-1 bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-mono text-xs text-primary" readonly>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Tournament Logo</label>
                    <div class="flex items-center gap-8">
                        @if(isset($settings['site_logo']))
                        <div class="w-24 h-24 bg-zinc-50 rounded-2xl border border-zinc-100 flex items-center justify-center p-4">
                            <img src="{{ $settings['site_logo'] }}" class="max-w-full max-h-full object-contain">
                        </div>
                        @else
                        <div class="w-24 h-24 bg-zinc-50 rounded-2xl border border-dashed border-zinc-200 flex items-center justify-center text-[10px] font-black text-zinc-300 uppercase text-center p-4 tracking-tighter">
                            No Logo Uploaded
                        </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="site_logo" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" accept="image/*">
                            <p class="text-[9px] text-zinc-400 mt-2 font-bold uppercase tracking-widest italic">Recommended: Transparent PNG, square or vertical ratio</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4">
            <button type="submit" class="bg-primary text-secondary font-black px-12 py-5 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-xs shadow-xl flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Publish Changes
            </button>
        </div>
    </form>
</div>
@endsection
