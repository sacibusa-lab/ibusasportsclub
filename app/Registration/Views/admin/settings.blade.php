@extends('admin.layout')

@section('title', 'Registration Settings')

@section('content')
<div class="max-w-4xl space-y-8">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.registrations.index') }}" class="inline-flex items-center gap-2 text-zinc-500 hover:text-primary dark:text-zinc-400 dark:hover:text-white transition font-bold text-xs uppercase">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to registrations
        </a>
    </div>

    <!-- Settings Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-sm border border-zinc-100 dark:border-zinc-800">
        <h2 class="text-xl font-black text-primary dark:text-white uppercase tracking-tight mb-2">Configure Registration Parameters</h2>
        <p class="text-zinc-400 text-xs font-semibold mb-8">Manage public fees, instruct teams, and control registration active states.</p>

        <form action="{{ route('admin.registrations.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Registration Instructions -->
            <div>
                <label for="registration_instructions" class="block text-xs font-black uppercase text-zinc-400 mb-2">Public Instructions Text *</label>
                <textarea name="registration_instructions" id="registration_instructions" rows="6" required class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-xs font-bold text-primary dark:text-white focus:border-secondary outline-none transition leading-relaxed">{{ old('registration_instructions', $settings['registration_instructions'] ?? '') }}</textarea>
                <span class="text-[10px] text-zinc-400 font-semibold mt-1 block">Renders on the public instructions landing page. Supports multi-line paragraphs.</span>
                @error('registration_instructions')
                <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pricing Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-zinc-100 dark:border-zinc-800 pt-6">
                <div>
                    <label for="registration_phase1_fee" class="block text-xs font-black uppercase text-zinc-400 mb-2">Phase 1 Fee (Slot Reservation) *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-zinc-400 font-bold text-xs">₦</span>
                        <input type="number" name="registration_phase1_fee" id="registration_phase1_fee" value="{{ old('registration_phase1_fee', $settings['registration_phase1_fee'] ?? '5000') }}" min="0" required class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl pl-8 pr-4 py-3.5 text-xs font-black text-primary dark:text-white focus:border-secondary outline-none transition">
                    </div>
                    @error('registration_phase1_fee')
                    <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="registration_phase2_fee" class="block text-xs font-black uppercase text-zinc-400 mb-2">Phase 2 Fee (Full Tournament) *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-zinc-400 font-bold text-xs">₦</span>
                        <input type="number" name="registration_phase2_fee" id="registration_phase2_fee" value="{{ old('registration_phase2_fee', $settings['registration_phase2_fee'] ?? '15000') }}" min="0" required class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl pl-8 pr-4 py-3.5 text-xs font-black text-primary dark:text-white focus:border-secondary outline-none transition">
                    </div>
                    @error('registration_phase2_fee')
                    <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Paystack API Credentials -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-zinc-100 dark:border-zinc-800 pt-6">
                <div>
                    <label for="paystack_public_key" class="block text-xs font-black uppercase text-zinc-400 mb-2">Paystack Public Key</label>
                    <input type="text" name="paystack_public_key" id="paystack_public_key" value="{{ old('paystack_public_key', $settings['paystack_public_key'] ?? '') }}" placeholder="e.g. pk_test_xxxxxx" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-3.5 text-xs font-bold text-primary dark:text-white focus:border-secondary outline-none transition">
                    <span class="text-[9px] text-zinc-400 font-semibold mt-1 block">Leave empty or set to placeholder to run in simulation mode.</span>
                    @error('paystack_public_key')
                    <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="paystack_secret_key" class="block text-xs font-black uppercase text-zinc-400 mb-2">Paystack Secret Key</label>
                    <input type="text" name="paystack_secret_key" id="paystack_secret_key" value="{{ old('paystack_secret_key', $settings['paystack_secret_key'] ?? '') }}" placeholder="e.g. sk_test_xxxxxx" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-3.5 text-xs font-bold text-primary dark:text-white focus:border-secondary outline-none transition">
                    <span class="text-[9px] text-zinc-400 font-semibold mt-1 block">Secret token used for server-side verification.</span>
                    @error('paystack_secret_key')
                    <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Active States Toggles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-zinc-100 dark:border-zinc-800 pt-6">
                <div>
                    <label for="registration_phase1_active" class="block text-xs font-black uppercase text-zinc-400 mb-2">Phase 1 Status *</label>
                    <select name="registration_phase1_active" id="registration_phase1_active" required class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-xs font-bold text-primary dark:text-white focus:border-secondary outline-none transition">
                        <option value="1" {{ old('registration_phase1_active', $settings['registration_phase1_active'] ?? '1') == '1' ? 'selected' : '' }}>Active (Accepting Reservations)</option>
                        <option value="0" {{ old('registration_phase1_active', $settings['registration_phase1_active'] ?? '1') == '0' ? 'selected' : '' }}>Closed (Decline Entry submissions)</option>
                    </select>
                    @error('registration_phase1_active')
                    <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="registration_phase2_active" class="block text-xs font-black uppercase text-zinc-400 mb-2">Phase 2 Status *</label>
                    <select name="registration_phase2_active" id="registration_phase2_active" required class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-xs font-bold text-primary dark:text-white focus:border-secondary outline-none transition">
                        <option value="1" {{ old('registration_phase2_active', $settings['registration_phase2_active'] ?? '1') == '1' ? 'selected' : '' }}>Active (Allow Roster submissions)</option>
                        <option value="0" {{ old('registration_phase2_active', $settings['registration_phase2_active'] ?? '1') == '0' ? 'selected' : '' }}>Closed (Decline Roster submissions)</option>
                    </select>
                    @error('registration_phase2_active')
                    <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="border-t border-zinc-100 dark:border-zinc-800 pt-6">
                <button type="submit" class="w-full bg-primary text-white font-black py-4 rounded-xl hover:bg-zinc-950 transition duration-200 text-xs uppercase tracking-widest">
                    Save Configuration
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
