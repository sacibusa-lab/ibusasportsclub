@extends('layout')

@section('title', 'Payment Successful! | ' . ($siteSettings['site_name'] ?? 'LC'))

@section('content')
<div class="max-w-xl mx-auto my-16 text-center">
    <div class="bg-white rounded-3xl p-10 shadow-2xl border border-zinc-100 relative overflow-hidden">
        
        <!-- Big Checkmark Animation Container -->
        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-8 shadow-lg shadow-emerald-500/10 animate-bounce">
            ✓
        </div>

        <h1 class="text-3xl font-black text-primary uppercase tracking-tight mb-2">Payment Successful!</h1>
        <p class="text-zinc-500 text-sm font-semibold mb-8">{{ $message }}</p>

        <!-- Dynamic Content based on Phase -->
        @if($phase === 1)
        <div class="bg-zinc-50 rounded-2xl p-6 border border-zinc-100 mb-8">
            <span class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Your Registration Code</span>
            <div class="text-3xl font-mono font-black text-primary tracking-widest select-all bg-white border border-zinc-200 py-3 px-4 rounded-xl shadow-inner">
                {{ $registration->registration_code }}
            </div>
            <p class="text-[10px] text-zinc-400 font-semibold mt-3 leading-relaxed">
                Save this code! You will need to enter this code on the registration portal to proceed to **Phase 2 (Roster Upload & Lock)**.
            </p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('registration.phase2.form', ['code' => $registration->registration_code]) }}" class="block w-full bg-primary text-white font-black py-4 rounded-xl hover:bg-zinc-950 transition duration-300 text-xs uppercase tracking-widest shadow-lg shadow-primary/10">
                Proceed to Phase 2 (Upload Roster)
            </a>
            <a href="{{ route('registration.instructions') }}" class="block w-full bg-zinc-50 border border-zinc-200 text-zinc-500 hover:text-primary hover:bg-zinc-100 font-bold py-4 rounded-xl transition text-xs uppercase tracking-widest">
                Back to Portal Home
            </a>
        </div>
        @else
        <!-- Phase 2 Success details -->
        <div class="bg-zinc-50 rounded-2xl p-6 border border-zinc-100 mb-8 space-y-3 text-left text-xs font-semibold text-zinc-500">
            <div class="flex justify-between border-b border-zinc-200/50 pb-2">
                <span>Registered Team:</span>
                <span class="text-primary font-bold uppercase">{{ $registration->team_name }}</span>
            </div>
            <div class="flex justify-between border-b border-zinc-200/50 pb-2">
                <span>Roster Count:</span>
                <span class="text-primary font-bold">{{ count($registration->phase2_data['players'] ?? []) }} Players</span>
            </div>
            <div class="flex justify-between border-b border-zinc-200/50 pb-2">
                <span>Payment Status:</span>
                <span class="text-emerald-600 font-bold uppercase">{{ $registration->status === 'completed' ? '100% Fully Paid' : '60% Deposit Paid' }}</span>
            </div>
            <div class="flex justify-between">
                <span>Contact Name:</span>
                <span class="text-primary font-bold">{{ $registration->contact_name }}</span>
            </div>
        </div>

        <div class="space-y-4">
            <a href="{{ route('registration.dashboard', ['code' => $registration->registration_code]) }}" class="block w-full bg-primary text-white font-black py-4 rounded-xl hover:bg-zinc-950 transition duration-300 text-xs uppercase tracking-widest shadow-lg shadow-primary/10">
                Go to Team Dashboard
            </a>
            <a href="{{ route('home') }}" class="block w-full bg-zinc-50 border border-zinc-200 text-zinc-500 hover:text-primary hover:bg-zinc-100 font-bold py-4 rounded-xl transition text-xs uppercase tracking-widest">
                Go to Tournament Homepage
            </a>
        </div>
        @endif

        <hr class="my-8 border-zinc-100">

        <div class="flex justify-center items-center gap-2 text-zinc-400 text-[10px] font-bold uppercase">
            <span>Reference:</span>
            <span class="font-mono text-zinc-500">{{ $registration->phase2_balance_ref ?? $registration->phase2_payment_ref ?? $registration->phase1_payment_ref }}</span>
        </div>
    </div>
</div>
@endsection
