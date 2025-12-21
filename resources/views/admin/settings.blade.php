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

        <!-- Files & Icons -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-8 border-b border-zinc-50 pb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Files & Icons
            </h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Favicon (.ico or .png)</label>
                    <div class="flex items-center gap-4">
                        @if(isset($settings['favicon']))
                        <div class="w-12 h-12 bg-zinc-50 rounded-xl border border-zinc-100 flex items-center justify-center p-2">
                            <img src="{{ $settings['favicon'] }}" class="max-w-full max-h-full object-contain">
                        </div>
                        @endif
                        <input type="file" name="favicon" class="flex-1 bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-bold text-primary text-[10px]" accept="image/x-icon,image/png">
                    </div>
                </div>
                <div class="space-y-4">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Site Icon (Small App Icon)</label>
                    <div class="flex items-center gap-4">
                        @if(isset($settings['site_icon']))
                        <div class="w-12 h-12 bg-zinc-50 rounded-xl border border-zinc-100 flex items-center justify-center p-2">
                            <img src="{{ $settings['site_icon'] }}" class="max-w-full max-h-full object-contain">
                        </div>
                        @endif
                        <input type="file" name="site_icon" class="flex-1 bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-bold text-primary text-[10px]" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer & Legal -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-8 border-b border-zinc-50 pb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Footer & Legal
            </h3>
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Footer Description</label>
                    <textarea name="footer_text" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs h-24">{{ $settings['footer_text'] }}</textarea>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest px-1">Copyright Text</label>
                    <input type="text" name="copyright_text" value="{{ $settings['copyright_text'] }}" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 pb-12">
            <button type="submit" class="bg-primary text-secondary font-black px-12 py-5 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-xs shadow-xl flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Publish Changes
            </button>
        </div>
    </form>
    
    <!-- Maintenance Section -->
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
        <h3 class="text-xs font-black text-rose-500 uppercase tracking-widest mb-8 border-b border-rose-50 pb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            System Maintenance
        </h3>
        <div class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                <div>
                    <h4 class="text-xs font-black text-primary uppercase italic">Fix Storage Link</h4>
                    <p class="text-[10px] text-zinc-400 font-bold uppercase mt-1">Run this if images are not showing on your live site (cPanel)</p>
                </div>
                <form action="{{ route('admin.fix-storage') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-white text-primary border border-zinc-200 font-black px-6 py-3 rounded-xl hover:bg-zinc-100 transition uppercase tracking-widest text-[10px] shadow-sm">
                        Fix Link
                    </button>
                </form>
            </div>

            <div class="flex items-center justify-between p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                <div>
                    <h4 class="text-xs font-black text-rose-600 uppercase italic">Force Sync Files (Last Resort)</h4>
                    <p class="text-[10px] text-zinc-400 font-bold uppercase mt-1">Stops using links and copies EVERYTHING directly to the web folder. This ALWAYS fixed 403 errors.</p>
                </div>
                <form action="{{ route('admin.sync-storage') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-rose-500 text-white font-black px-6 py-3 rounded-xl hover:bg-rose-600 transition uppercase tracking-widest text-[10px] shadow-sm">
                        Sync Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
