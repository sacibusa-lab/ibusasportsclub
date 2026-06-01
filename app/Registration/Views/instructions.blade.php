@extends('layout')

@section('title', 'Competition Registration | ' . ($siteSettings['site_name'] ?? 'LC'))

@section('content')
<div class="max-w-4xl mx-auto my-12">
    <!-- Header Hero Banner -->
    <div class="hero-gradient text-white rounded-3xl p-8 md:p-12 shadow-2xl mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="bg-secondary text-primary text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Official Registration</span>
                <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase mt-4 mb-2">Join the Tournament</h1>
                <p class="text-white/80 font-medium text-sm md:text-base max-w-xl">
                    Register your team today to compete, gain recognition, and play at the highest amateur level in the region.
                </p>
            </div>
            <div class="shrink-0 flex gap-3">
                @if(($settings['registration_phase1_active'] ?? '1') === '1')
                <a href="{{ route('registration.phase1') }}" class="bg-secondary text-primary font-black px-6 py-4 rounded-2xl hover:scale-105 hover:shadow-xl transition duration-300 text-center text-sm uppercase tracking-wider">
                    Register Now
                </a>
                @endif
                <a href="{{ route('registration.phase2.access') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold px-6 py-4 rounded-2xl border border-white/10 text-center text-sm transition">
                    Roster / Phase 2
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        
        <!-- Instructions Panel -->
        <div class="md:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
                <h2 class="text-2xl font-black text-primary uppercase tracking-tight mb-4">Registration Instructions</h2>
                <div class="text-zinc-600 text-sm leading-relaxed space-y-4 font-medium">
                    {!! nl2br(e($settings['registration_instructions'] ?? 'Welcome to the Tournament Registration portal! Please follow the steps on the right to complete registration.')) !!}
                </div>
            </div>

            <!-- Steps Details -->
            <div class="space-y-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400">Registration Process</h3>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-zinc-100 flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center font-black text-primary text-lg shrink-0">
                        1
                    </div>
                    <div>
                        <h4 class="font-black text-primary text-base uppercase">Phase 1: Slot Reservation</h4>
                        <p class="text-zinc-500 text-xs mt-1 leading-relaxed">
                            Fill out basic team contacts and make payment of the participation fee. Upon confirmation, you will receive a unique **Registration Code** to access Phase 2.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-zinc-100 flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center font-black text-primary text-lg shrink-0">
                        2
                    </div>
                    <div>
                        <h4 class="font-black text-primary text-base uppercase">Phase 2: Roster Upload & Lock</h4>
                        <p class="text-zinc-500 text-xs mt-1 leading-relaxed">
                            Enter your unique Registration Code to log in. Upload your full squad list (names, positions, shirt numbers, dates of birth), pay the tournament fee, and lock your roster.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-zinc-100 flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center font-black text-primary text-lg shrink-0">
                        3
                    </div>
                    <div>
                        <h4 class="font-black text-primary text-base uppercase">Admin Approval & Verification</h4>
                        <p class="text-zinc-500 text-xs mt-1 leading-relaxed">
                            The administration reviews player credentials. Once verified, your team is created in the official tournament tables and schedule automatically!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Fees & Status -->
        <div class="space-y-6">
            
            <!-- Phase 1 Fee Card -->
            <div class="bg-zinc-900 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                <span class="text-[9px] font-black uppercase text-secondary tracking-widest">Phase 1 Fee</span>
                <div class="text-3xl font-black tracking-tight mt-2 flex items-baseline gap-1">
                    ₦{{ number_format(floatval($settings['registration_phase1_fee'] ?? 5000)) }}
                    <span class="text-xs text-zinc-400 font-medium">/ slot</span>
                </div>
                <p class="text-zinc-400 text-[11px] mt-2 leading-relaxed">
                    Secures your team a spot in the group draw. Required before team uploads.
                </p>
                <hr class="my-4 border-white/5">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-zinc-500 font-medium">Status</span>
                    @if(($settings['registration_phase1_active'] ?? '1') === '1')
                    <span class="bg-secondary/15 text-secondary font-black px-2 py-0.5 rounded-full uppercase text-[9px]">Active</span>
                    @else
                    <span class="bg-rose-500/20 text-rose-400 font-black px-2 py-0.5 rounded-full uppercase text-[9px]">Closed</span>
                    @endif
                </div>
            </div>

            <!-- Phase 2 Fee Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-zinc-100 relative overflow-hidden">
                <span class="text-[9px] font-black uppercase text-zinc-400 tracking-widest">Phase 2 Fee</span>
                <div class="text-3xl font-black text-primary tracking-tight mt-2 flex items-baseline gap-1">
                    ₦{{ number_format(floatval($settings['registration_phase2_fee'] ?? 15000)) }}
                    <span class="text-xs text-zinc-400 font-medium">/ team</span>
                </div>
                <p class="text-zinc-500 text-[11px] mt-2 leading-relaxed">
                    Full tournament registration fee. Covers jerseys, matches, and referee fees.
                </p>
                <hr class="my-4 border-zinc-100">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-zinc-400 font-medium">Status</span>
                    @if(($settings['registration_phase2_active'] ?? '1') === '1')
                    <span class="bg-emerald-100 text-emerald-800 font-black px-2 py-0.5 rounded-full uppercase text-[9px]">Active</span>
                    @else
                    <span class="bg-rose-100 text-rose-800 font-black px-2 py-0.5 rounded-full uppercase text-[9px]">Closed</span>
                    @endif
                </div>
            </div>

            <!-- Contacts Helper -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-zinc-100">
                <h4 class="font-black text-primary text-xs uppercase mb-3">Support & Questions</h4>
                <p class="text-zinc-500 text-[11px] leading-relaxed mb-4">
                    For inquiries about rules, pricing, or technical issues, reach out to the support desk.
                </p>
                <a href="mailto:{{ $siteSettings['contact_email'] ?? 'admin@tournament.com' }}" class="block text-center text-xs font-black text-primary bg-zinc-50 hover:bg-zinc-100 border border-zinc-100 py-3 rounded-xl transition">
                    Contact Admin
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
