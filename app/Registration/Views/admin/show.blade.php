@extends('admin.layout')

@section('title', 'Registration Details')

@section('content')
<div class="space-y-8">
    
    <!-- Navigation & Page Title -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.registrations.index') }}" class="inline-flex items-center gap-2 text-zinc-500 hover:text-primary dark:text-zinc-400 dark:hover:text-white transition font-bold text-xs uppercase">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to registrations
        </a>
        <span class="text-zinc-400 text-xs font-bold uppercase">Code: <span class="font-mono text-primary dark:text-white select-all">{{ $registration->registration_code ?? 'N/A' }}</span></span>
    </div>

    <!-- Main Detail Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Registration Details & Metadata -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Quick Info Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-100 dark:border-zinc-800">
                <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest block mb-2">Registration Status</span>
                
                <div class="mb-4">
                    @if($registration->status === 'completed')
                    <span class="bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-black px-3 py-1 rounded-full uppercase text-[10px]">Fully Completed (100% Paid)</span>
                    @elseif($registration->status === 'partially_paid')
                    <span class="bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-black px-3 py-1 rounded-full uppercase text-[10px]">Partially Paid (60% Deposit)</span>
                    @elseif($registration->status === 'phase1_paid')
                    <span class="bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 font-black px-3 py-1 rounded-full uppercase text-[10px]">Phase 1 Paid</span>
                    @else
                    <span class="bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 font-black px-3 py-1 rounded-full uppercase text-[10px]">Initiated (Unpaid)</span>
                    @endif
                </div>

                <hr class="my-4 border-zinc-100 dark:border-zinc-800">

                <div class="space-y-4">
                    <div>
                        <span class="block text-[10px] text-zinc-400 font-bold uppercase">Team Name</span>
                        <span class="block text-sm font-black text-primary dark:text-white uppercase">{{ $registration->team_name }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-zinc-400 font-bold uppercase">Tournament</span>
                        <span class="block text-xs font-bold text-primary dark:text-zinc-300 uppercase">{{ $registration->competition->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-zinc-400 font-bold uppercase">Designated Representative (Manager)</span>
                        <span class="block text-xs font-bold text-primary dark:text-zinc-300">{{ $registration->contact_name }}</span>
                        <span class="block text-[10px] text-zinc-400 mt-0.5">{{ $registration->contact_phone }} | {{ $registration->contact_email }}</span>
                    </div>
                    @if(isset($registration->phase2_data['coach_name']))
                    <div>
                        <span class="block text-[10px] text-zinc-400 font-bold uppercase">Head Coach</span>
                        <span class="block text-xs font-bold text-primary dark:text-zinc-300">{{ $registration->phase2_data['coach_name'] }}</span>
                        <span class="block text-[10px] text-zinc-400 mt-0.5">{{ $registration->phase2_data['coach_phone'] ?? 'N/A' }} | {{ $registration->phase2_data['coach_email'] ?? 'N/A' }}</span>
                    </div>
                    @endif
                    @if(isset($registration->phase2_data['alumni_letter_url']))
                    <div class="pt-2">
                        <span class="block text-[10px] text-zinc-400 font-bold uppercase mb-1">Verification Document</span>
                        <a href="{{ $registration->phase2_data['alumni_letter_url'] }}" target="_blank" class="inline-flex items-center gap-1.5 bg-primary text-white dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700 hover:bg-zinc-950 font-black px-3 py-2 rounded-lg text-[9px] uppercase tracking-wider transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Alumni Verification Letter
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Phase 1 Payment Info -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-100 dark:border-zinc-800">
                <h3 class="text-xs font-black text-primary dark:text-white uppercase tracking-tight mb-4 border-b border-zinc-100 dark:border-zinc-800 pb-2">Phase 1: Slot Reservation</h3>
                <div class="space-y-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                    <div class="flex justify-between">
                        <span>Amount Paid:</span>
                        <span class="text-primary dark:text-white font-black">₦{{ number_format($registration->phase1_amount) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Payment Status:</span>
                        <span class="uppercase font-black text-emerald-600">{{ $registration->phase1_payment_status }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Reference:</span>
                        <span class="font-mono text-primary dark:text-zinc-300 text-[10px]">{{ $registration->phase1_payment_ref ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Paid At:</span>
                        <span class="text-primary dark:text-zinc-300">{{ $registration->phase1_paid_at ? $registration->phase1_paid_at->format('M d, Y h:i A') : 'N/A' }}</span>
                    </div>
                    @if(isset($registration->phase1_data['president_name']))
                    <div class="flex justify-between border-t border-zinc-100 dark:border-zinc-800 pt-2 mt-2">
                        <span>Set President:</span>
                        <span class="text-primary dark:text-zinc-300 font-bold">{{ $registration->phase1_data['president_name'] }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Phase 2 Payment Info -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-100 dark:border-zinc-800">
                <h3 class="text-xs font-black text-primary dark:text-white uppercase tracking-tight mb-4 border-b border-zinc-100 dark:border-zinc-800 pb-2">Phase 2: Split Payments</h3>
                <div class="space-y-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                    <!-- 60% Deposit -->
                    <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3">
                        <span class="block text-[10px] text-zinc-400 font-bold uppercase mb-1">60% Registration Deposit</span>
                        <div class="flex justify-between">
                            <span>Amount:</span>
                            <span class="text-primary dark:text-white font-black">₦{{ number_format($registration->phase2_amount * 0.60) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span class="uppercase font-black {{ $registration->phase2_payment_status === 'paid' ? 'text-emerald-600' : 'text-zinc-400' }}">{{ $registration->phase2_payment_status }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Paid At:</span>
                            <span class="text-primary dark:text-zinc-300">{{ $registration->phase2_paid_at ? $registration->phase2_paid_at->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Reference:</span>
                            <span class="font-mono text-primary dark:text-zinc-300 text-[10px]">{{ $registration->phase2_payment_ref ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- 40% Balance -->
                    <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3">
                        <span class="block text-[10px] text-zinc-400 font-bold uppercase mb-1">40% Kickoff Balance</span>
                        <div class="flex justify-between">
                            <span>Amount:</span>
                            <span class="text-primary dark:text-white font-black">₦{{ number_format($registration->phase2_amount * 0.40) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span class="uppercase font-black {{ $registration->phase2_balance_status === 'paid' ? 'text-emerald-600' : 'text-zinc-400' }}">{{ $registration->phase2_balance_status }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Paid At:</span>
                            <span class="text-primary dark:text-zinc-300">{{ $registration->phase2_balance_paid_at ? $registration->phase2_balance_paid_at->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Reference:</span>
                            <span class="font-mono text-primary dark:text-zinc-300 text-[10px]">{{ $registration->phase2_balance_ref ?? 'N/A' }}</span>
                        </div>
                    </div>

                    @if(isset($registration->phase2_data['jersey_home']))
                    <div class="flex justify-between pt-2">
                        <span>Home Kit:</span>
                        <span class="text-primary dark:text-zinc-300">{{ $registration->phase2_data['jersey_home'] }}</span>
                    </div>
                    @endif
                    @if(isset($registration->phase2_data['jersey_away']))
                    <div class="flex justify-between">
                        <span>Away Kit:</span>
                        <span class="text-primary dark:text-zinc-300">{{ $registration->phase2_data['jersey_away'] }}</span>
                    </div>
                    @endif
                    @if(isset($registration->phase2_data['primary_color']))
                    <div class="flex justify-between items-center">
                        <span>Primary Color:</span>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3.5 h-3.5 rounded-full border border-zinc-300 inline-block" style="background-color: {{ $registration->phase2_data['primary_color'] }}"></span>
                            <span class="text-primary dark:text-zinc-300 uppercase">{{ $registration->phase2_data['primary_color'] }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Roster / Squad Players List -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-sm border border-zinc-100 dark:border-zinc-800">
                <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-4 mb-6">
                    <h3 class="text-lg font-black text-primary dark:text-white uppercase tracking-tight">Squad Roster</h3>
                    <span class="bg-primary text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ count($registration->phase2_data['players'] ?? []) }} Players
                    </span>
                </div>

                @if(isset($registration->phase2_data['players']) && count($registration->phase2_data['players']) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($registration->phase2_data['players'] as $index => $player)
                    <div class="bg-zinc-50 dark:bg-zinc-950 rounded-2xl p-4 border border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-zinc-200/50 dark:bg-zinc-800 flex items-center justify-center font-black text-primary dark:text-white text-xs">
                                {{ $player['shirt_number'] ?? '?' }}
                            </div>
                            <div>
                                <span class="block font-black text-primary dark:text-white uppercase text-xs">{{ $player['name'] }}</span>
                                <span class="block text-[9px] text-zinc-400 font-semibold uppercase mt-0.5">{{ $player['position'] ?? 'N/A' }}</span>
                                @if(isset($player['id_url']))
                                <a href="{{ $player['id_url'] }}" target="_blank" class="inline-flex items-center gap-1 text-[9px] font-black text-zinc-400 hover:text-primary dark:text-zinc-400 dark:hover:text-white transition mt-1 uppercase tracking-wider">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    View ID Card
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-[8px] text-zinc-400 font-bold uppercase tracking-wider">Date of Birth</span>
                            <span class="block text-[10px] font-bold text-primary dark:text-zinc-300 mt-0.5">
                                {{ isset($player['dob']) ? \Carbon\Carbon::parse($player['dob'])->format('M d, Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="py-16 text-center">
                    <div class="w-12 h-12 bg-zinc-100 dark:bg-zinc-800 text-zinc-400 rounded-full flex items-center justify-center text-lg mx-auto mb-4">
                        ?
                    </div>
                    <h4 class="font-black text-primary dark:text-white uppercase text-xs">Roster Not Uploaded</h4>
                    <p class="text-zinc-400 text-[10px] mt-1 max-w-xs mx-auto leading-relaxed">
                        The team has not uploaded their roster list yet. Roster uploads will unlock once the team completes Phase 1 payment.
                    </p>
                </div>
                @endif
            </div>

            <!-- Auto Sync Notification -->
            @if($registration->status === 'completed')
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-800 dark:text-emerald-400 rounded-3xl p-6 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                    ✓
                </div>
                <div>
                    <h4 class="font-black uppercase text-xs text-primary dark:text-white">Official Database Synced</h4>
                    <p class="text-[10px] leading-relaxed mt-1 font-semibold text-zinc-500 dark:text-zinc-400">
                        This team has been automatically synchronized to the official database. You can manage them in the main 
                        <a href="{{ route('admin.teams') }}" class="text-primary dark:text-white underline font-black">Teams Panel</a>.
                    </p>
                </div>
            </div>
            @endif

        </div>

    </div>
</div>
@endsection
