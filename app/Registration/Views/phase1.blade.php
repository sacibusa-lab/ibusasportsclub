@extends('layout')

@section('title', 'Phase 1: Reserve Team Slot | ' . ($siteSettings['site_name'] ?? 'LC'))

@section('content')
    <div class="max-w-4xl mx-auto my-12">
        <!-- Back Navigation -->
        <a href="{{ route('registration.instructions') }}"
            class="inline-flex items-center gap-2 text-zinc-500 hover:text-primary transition font-bold text-xs uppercase mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to instructions
        </a>

        @if(session('error'))
            <div
                class="mb-8 bg-rose-500 text-white px-6 py-4 rounded-2xl font-black shadow-lg flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white/40 hover:text-white">✕</button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- Form Container -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
                <h2 class="text-2xl font-black text-primary uppercase tracking-tight mb-2">Phase 1: Participation Form</h2>
                <p class="text-zinc-400 text-xs font-semibold mb-8">Enter your set details and contact representative
                    information. Registration is finalized upon payment validation.</p>

                <form action="{{ route('registration.phase1.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Competition Select -->
                    <div>
                        <label for="competition_id" class="block text-xs font-black uppercase text-zinc-400 mb-2">Select
                            Competition *</label>
                        <select name="competition_id" id="competition_id" required
                            class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 text-sm font-bold text-primary focus:border-secondary focus:bg-white outline-none transition">
                            <option value="">-- Choose tournament --</option>
                            @foreach($competitions as $comp)
                                <option value="{{ $comp->id }}" {{ old('competition_id') == $comp->id ? 'selected' : '' }}>
                                    {{ $comp->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('competition_id')
                            <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Set Name & President -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="team_name" class="block text-xs font-black uppercase text-zinc-400 mb-2">Set Name /
                                Alumni Association Name *</label>
                            <input type="text" name="team_name" id="team_name" value="{{ old('team_name') }}" required
                                placeholder="e.g. Class of 2005"
                                class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 text-sm font-bold text-primary focus:border-secondary focus:bg-white outline-none transition">
                            @error('team_name')
                                <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="president_name" class="block text-xs font-black uppercase text-zinc-400 mb-2">Name
                                of Set President *</label>
                            <input type="text" name="president_name" id="president_name" value="{{ old('president_name') }}"
                                required placeholder="e.g. John Doe"
                                class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 text-sm font-bold text-primary focus:border-secondary focus:bg-white outline-none transition">
                            @error('president_name')
                                <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Representative Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-zinc-100 pt-6">
                        <div class="md:col-span-2">
                            <h3 class="text-xs font-black uppercase text-primary tracking-wide mb-1">Designated
                                Representative Info</h3>
                            <p class="text-[10px] text-zinc-400 font-semibold mb-4">This person will manage the registration
                                and receive login credentials for roster submissions.</p>
                        </div>

                        <div>
                            <label for="contact_name" class="block text-xs font-black uppercase text-zinc-400 mb-2">Name of
                                Designated Representative *</label>
                            <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}"
                                required placeholder="e.g. Jane Smith"
                                class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 text-sm font-bold text-primary focus:border-secondary focus:bg-white outline-none transition">
                            @error('contact_name')
                                <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-xs font-black uppercase text-zinc-400 mb-2">Phone
                                Number of Representative *</label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}"
                                required placeholder="e.g. +234 803 123 4567"
                                class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 text-sm font-bold text-primary focus:border-secondary focus:bg-white outline-none transition">
                            <span class="text-[10px] text-zinc-400 font-semibold mt-1 block">Used to receive verification
                                and Phase 2 login codes.</span>
                            @error('contact_phone')
                                <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="contact_email"
                                class="block text-xs font-black uppercase text-zinc-400 mb-2">Representative Email Address
                                *</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}"
                                required placeholder="e.g. representative@alumni.com"
                                class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 text-sm font-bold text-primary focus:border-secondary focus:bg-white outline-none transition">
                            @error('contact_email')
                                <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-white font-black py-4 rounded-2xl hover:bg-zinc-950 transition duration-300 flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                        Proceed to Payment
                    </button>
                </form>
            </div>

            <!-- Sidebar pricing info -->
            <div class="space-y-6">
                <div class="bg-zinc-900 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
                    <span class="text-[9px] font-black uppercase text-secondary tracking-widest">Order Summary</span>
                    <div class="text-3xl font-black tracking-tight mt-2">
                        ₦{{ number_format(floatval($settings['registration_phase1_fee'] ?? 5000)) }}
                    </div>
                    <p class="text-zinc-400 text-[11px] mt-2 leading-relaxed">
                        Participation form registration & slot reservation fee. Secure checkout is managed securely by
                        Paystack.
                    </p>
                    <hr class="my-4 border-white/5">
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-zinc-500 font-medium">Slot Reservation</span>
                            <span
                                class="font-bold">₦{{ number_format(floatval($settings['registration_phase1_fee'] ?? 5000)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500 font-medium">Processing Fee</span>
                            <span class="font-bold">₦0.00</span>
                        </div>
                        <hr class="border-white/5 my-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-400 font-bold">Total Due</span>
                            <span
                                class="text-secondary font-black">₦{{ number_format(floatval($settings['registration_phase1_fee'] ?? 5000)) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm border border-zinc-100 flex items-center gap-4">
                    <div
                        class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                        ✓
                    </div>
                    <div class="text-[10px] text-zinc-500 leading-relaxed font-semibold">
                        Paystack secure payment active. Real-time authorization. Your details are processed via an encrypted
                        SSL channel.
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection