@extends('layout')

@section('title', 'Registration Dashboard | ' . ($siteSettings['site_name'] ?? 'LC'))

@section('content')
<div class="max-w-7xl mx-auto my-12 px-4">
    <!-- Header Hero Banner -->
    <div class="hero-gradient text-white rounded-3xl p-8 md:p-12 shadow-2xl mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="bg-secondary text-primary text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">
                    {{ $registration->status === 'completed' ? 'Fully Registered' : 'Partially Registered' }}
                </span>
                <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase mt-4 mb-2">
                    {{ $registration->team_name }}
                </h1>
                <p class="text-white/80 font-medium text-sm md:text-base max-w-xl">
                    Welcome to your team registration dashboard. Manage your players, documents, and payments here.
                </p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/15 text-right shrink-0">
                <span class="block text-[9px] font-black text-secondary uppercase tracking-widest">Registration Code</span>
                <span class="block text-xl font-mono font-black select-all text-white">{{ $registration->registration_code }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-8 bg-emerald-500 text-white px-6 py-4 rounded-2xl font-black shadow-lg flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-white/40 hover:text-white">✕</button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-8 bg-rose-500 text-white px-6 py-4 rounded-2xl font-black shadow-lg flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-white/40 hover:text-white">✕</button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!--Left / Roster and Info -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Manager and Coach Info Card -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
                <h3 class="text-lg font-black text-primary uppercase tracking-tight mb-6">Staff & Leadership</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Manager Details -->
                    <div class="space-y-3 bg-zinc-50 rounded-2xl p-5 border border-zinc-100">
                        <span class="bg-primary/10 text-primary text-[8px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider inline-block">Team Manager</span>
                        <div class="pt-2">
                            <span class="block text-xs font-black text-zinc-400 uppercase">Name</span>
                            <span class="block text-sm font-bold text-primary">{{ $registration->contact_name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-zinc-400 uppercase">Phone</span>
                            <span class="block text-sm font-bold text-primary">{{ $registration->contact_phone }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-zinc-400 uppercase">Email</span>
                            <span class="block text-sm font-bold text-primary">{{ $registration->contact_email }}</span>
                        </div>
                    </div>

                    <!-- Coach Details -->
                    <div class="space-y-3 bg-zinc-50 rounded-2xl p-5 border border-zinc-100">
                        <span class="bg-primary/10 text-primary text-[8px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider inline-block">Head Coach</span>
                        <div class="pt-2">
                            <span class="block text-xs font-black text-zinc-400 uppercase">Name</span>
                            <span class="block text-sm font-bold text-primary">{{ $registration->phase2_data['coach_name'] ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-zinc-400 uppercase">Phone</span>
                            <span class="block text-sm font-bold text-primary">{{ $registration->phase2_data['coach_phone'] ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-zinc-400 uppercase">Email</span>
                            <span class="block text-sm font-bold text-primary">{{ $registration->phase2_data['coach_email'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Squad List Roster Card -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
                <div class="flex items-center justify-between border-b border-zinc-100 pb-4 mb-6">
                    <h3 class="text-lg font-black text-primary uppercase tracking-tight">Official Squad Roster</h3>
                    <span class="bg-primary text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ count($registration->phase2_data['players'] ?? []) }} Players
                    </span>
                </div>

                @if(isset($registration->phase2_data['players']) && count($registration->phase2_data['players']) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($registration->phase2_data['players'] as $player)
                    <div class="bg-zinc-50 rounded-2xl p-4 border border-zinc-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-zinc-200/50 flex items-center justify-center font-black text-primary text-xs">
                                {{ $player['shirt_number'] ?? '?' }}
                            </div>
                            <div>
                                <span class="block font-black text-primary uppercase text-xs">{{ $player['name'] }}</span>
                                <span class="block text-[9px] text-zinc-400 font-semibold uppercase mt-0.5">{{ $player['position'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="text-right flex flex-col items-end gap-1">
                            @if(!empty($player['id_url']))
                            <a href="{{ $player['id_url'] }}" target="_blank" class="bg-zinc-200/50 hover:bg-zinc-200 text-zinc-600 font-black px-2 py-1 rounded-md text-[8px] uppercase tracking-wider flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                View ID
                            </a>
                            @else
                            <span class="text-[8px] font-bold text-rose-500 uppercase">Missing ID</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        <!-- Right / Fees and verification dashboard -->
        <div class="space-y-6">
            
            <!-- Payment Schedule Tracker Card -->
            <div class="bg-zinc-900 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <span class="text-[9px] font-black uppercase text-secondary tracking-widest block">Payment Schedule & Status</span>
                
                <div class="mt-4 flex items-center justify-between">
                    <div>
                        <span class="block text-[10px] text-zinc-400 font-bold uppercase">Paid So Far</span>
                        <span class="text-3xl font-black text-white">
                            ₦{{ number_format(floatval($registration->phase2_amount) * 0.60) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] text-zinc-400 font-bold uppercase">Balance Status</span>
                        @if($registration->status === 'completed')
                        <span class="bg-secondary/15 text-secondary font-black px-2 py-0.5 rounded-full uppercase text-[9px] border border-secondary/20">Fully Paid</span>
                        @else
                        <span class="bg-amber-500/20 text-amber-400 font-black px-2 py-0.5 rounded-full uppercase text-[9px] border border-amber-500/20">40% Balance Due</span>
                        @endif
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-zinc-800 rounded-full h-2 my-6">
                    <div class="bg-secondary h-2 rounded-full transition-all duration-500" style="width: {{ $registration->status === 'completed' ? '100' : '60' }}%"></div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-zinc-500 font-medium">60% Deposit</span>
                        <span class="font-bold text-white">₦{{ number_format(floatval($registration->phase2_amount) * 0.60) }} (✓ Paid)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500 font-medium">40% Kickoff Balance</span>
                        @if($registration->status === 'completed')
                        <span class="font-bold text-white">₦{{ number_format(floatval($registration->phase2_amount) * 0.40) }} (✓ Paid)</span>
                        @else
                        <span class="font-bold text-amber-400">₦{{ number_format(floatval($registration->phase2_amount) * 0.40) }} (Pending)</span>
                        @endif
                    </div>
                </div>

                @if($registration->status !== 'completed')
                <hr class="my-5 border-white/5">
                <form action="{{ route('registration.pay_balance', $registration->registration_code) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-secondary text-primary font-black py-4 rounded-xl hover:scale-[1.02] transition duration-200 uppercase tracking-wider text-xs">
                        Pay Remaining 40% Balance (₦{{ number_format(floatval($registration->phase2_amount) * 0.40) }})
                    </button>
                </form>
                @endif
            </div>

            <!-- Documents Review Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-zinc-100">
                <h4 class="text-xs font-black uppercase text-primary mb-4">Uploaded Documents</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3.5 bg-zinc-50 rounded-2xl border border-zinc-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                                📃
                            </div>
                            <div>
                                <span class="block font-black text-primary text-xs uppercase">Alumni Letter</span>
                                <span class="block text-[8px] text-zinc-400 font-semibold uppercase mt-0.5">Verification document</span>
                            </div>
                        </div>
                        @if(!empty($registration->phase2_data['alumni_letter_url']))
                        <a href="{{ $registration->phase2_data['alumni_letter_url'] }}" target="_blank" class="bg-white hover:bg-zinc-100 text-primary border border-zinc-200 font-black px-3 py-1.5 rounded-xl text-[9px] uppercase tracking-wider flex items-center gap-1 transition">
                            Open File
                        </a>
                        @else
                        <span class="text-[8px] font-bold text-rose-500 uppercase">Missing File</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
