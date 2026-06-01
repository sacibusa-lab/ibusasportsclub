@extends('layout')

@section('title', 'Phase 2: Roster Upload Login | ' . ($siteSettings['site_name'] ?? 'LC'))

@section('content')
<div class="max-w-md mx-auto my-20">
    <div class="bg-white rounded-3xl p-8 shadow-xl border border-zinc-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary via-secondary to-accent"></div>
        
        <h2 class="text-2xl font-black text-primary uppercase tracking-tight mb-2 mt-4 text-center">Enter Registration Code</h2>
        <p class="text-zinc-400 text-xs text-center font-semibold mb-8">Please enter the unique Registration Code generated after completing your Phase 1 slot reservation payment.</p>

        @if(session('error'))
        <div class="mb-6 bg-rose-500 text-white px-4 py-3 rounded-xl font-bold shadow-md text-xs">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('registration.phase2.verify') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="registration_code" class="block text-xs font-black uppercase text-zinc-400 mb-2">Registration Code</label>
                <input type="text" name="registration_code" id="registration_code" required placeholder="e.g. REG-A1B2C3D4" value="{{ old('registration_code') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-4 text-sm font-bold text-center tracking-widest text-primary focus:border-secondary focus:bg-white outline-none transition uppercase">
                @error('registration_code')
                <span class="text-rose-500 text-xs mt-1 block font-bold text-center">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-primary text-white font-black py-4 rounded-xl hover:bg-zinc-950 transition duration-300 flex items-center justify-center gap-2 text-xs uppercase tracking-widest">
                Verify Code & Continue
            </button>
        </form>

        <hr class="my-6 border-zinc-100">

        <div class="text-center">
            <a href="{{ route('registration.instructions') }}" class="text-xs font-bold text-zinc-500 hover:text-primary transition uppercase">
                ← Back to Portal Home
            </a>
        </div>
    </div>
</div>
@endsection
