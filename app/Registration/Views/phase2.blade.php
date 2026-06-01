@extends('layout')

@section('title', 'Phase 2: Roster & Roster Lock | ' . ($siteSettings['site_name'] ?? 'LC'))

@section('content')
<div class="max-w-5xl mx-auto my-12" x-data="{
    players: {!! json_encode(old('players', [['name' => '', 'shirt_number' => '', 'position' => 'Goalkeeper', 'dob' => '']])) !!},
    addPlayer() {
        this.players.push({ name: '', shirt_number: '', position: 'Defender', dob: '' });
    },
    removePlayer(index) {
        if (this.players.length > 1) {
            this.players.splice(index, 1);
        }
    }
}">
    <!-- Back Navigation -->
    <a href="{{ route('registration.phase2.access') }}" class="inline-flex items-center gap-2 text-zinc-500 hover:text-primary transition font-bold text-xs uppercase mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to verification
    </a>

    @if(session('error'))
    <div class="mb-8 bg-rose-500 text-white px-6 py-4 rounded-2xl font-black shadow-lg flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-white/40 hover:text-white">✕</button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Form Container -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-zinc-100 pb-6 mb-8">
                <div>
                    <h2 class="text-2xl font-black text-primary uppercase tracking-tight">Phase 2: Roster & Registration</h2>
                    <p class="text-zinc-400 text-xs font-semibold mt-1">Complete your team details and define your official roster.</p>
                </div>
                <div class="bg-zinc-100 border border-zinc-200 rounded-2xl px-4 py-2 text-right">
                    <span class="block text-[9px] font-black text-zinc-400 uppercase tracking-widest">Registered Team</span>
                    <span class="block text-sm font-black text-primary uppercase">{{ $registration->team_name }}</span>
                </div>
            </div>

            <form action="{{ route('registration.phase2.submit', $registration->registration_code) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Team Manager & Coach Section -->
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-4">Manager & Coach Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-zinc-50 rounded-2xl p-6 border border-zinc-100 mb-6">
                        <!-- Manager Info (Pre-filled from Phase 1 Contact) -->
                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black uppercase text-primary tracking-wider border-b border-zinc-200 pb-1">Team Manager (Representative)</h4>
                            <div>
                                <label for="contact_name" class="block text-[9px] font-black uppercase text-zinc-400 mb-1">Manager Name *</label>
                                <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', $registration->contact_name) }}" required class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                @error('contact_name')
                                <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="contact_phone" class="block text-[9px] font-black uppercase text-zinc-400 mb-1">Phone *</label>
                                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $registration->contact_phone) }}" required class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-[9px] font-black uppercase text-zinc-400 mb-1">Email *</label>
                                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $registration->contact_email) }}" required class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                </div>
                            </div>
                        </div>

                        <!-- Coach Info -->
                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black uppercase text-primary tracking-wider border-b border-zinc-200 pb-1">Head Coach Details</h4>
                            <div>
                                <label for="coach_name" class="block text-[9px] font-black uppercase text-zinc-400 mb-1">Coach Name *</label>
                                <input type="text" name="coach_name" id="coach_name" value="{{ old('coach_name') }}" required placeholder="e.g. Stephen Keshi" class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                @error('coach_name')
                                <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="coach_phone" class="block text-[9px] font-black uppercase text-zinc-400 mb-1">Phone *</label>
                                    <input type="text" name="coach_phone" id="coach_phone" value="{{ old('coach_phone') }}" required placeholder="e.g. +234..." class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                </div>
                                <div>
                                    <label for="coach_email" class="block text-[9px] font-black uppercase text-zinc-400 mb-1">Email *</label>
                                    <input type="email" name="coach_email" id="coach_email" value="{{ old('coach_email') }}" required placeholder="e.g. coach@alumni.com" class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alumni Verification Letter Section -->
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-4">Official Verification</h3>
                    
                    <div class="bg-zinc-50 rounded-2xl p-6 border border-zinc-100 mb-6">
                        <label for="alumni_letter" class="block text-[10px] font-black uppercase text-zinc-400 mb-2">Upload Official Alumni Verification Letter *</label>
                        <input type="file" name="alumni_letter" id="alumni_letter" required accept="image/*,application/pdf" class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                        <span class="text-[9px] text-zinc-400 font-semibold mt-1 block">Upload a PDF or Image (max 5MB). This must be signed by your class/set representative.</span>
                        @error('alumni_letter')
                        <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Colors & Uniform Section -->
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-4">Team Colors & Kit</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-zinc-50 rounded-2xl p-6 border border-zinc-100">
                        <div>
                            <label for="primary_color" class="block text-[10px] font-black uppercase text-zinc-400 mb-2">Primary Color Hex *</label>
                            <div class="flex gap-2">
                                <input type="color" id="primary_color_picker" value="{{ old('primary_color', '#3d195b') }}" class="w-10 h-10 border-0 p-0 rounded-lg cursor-pointer bg-transparent" oninput="document.getElementById('primary_color').value = this.value">
                                <input type="text" name="primary_color" id="primary_color" value="{{ old('primary_color', '#3d195b') }}" required class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition uppercase">
                            </div>
                            @error('primary_color')
                            <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="jersey_home" class="block text-[10px] font-black uppercase text-zinc-400 mb-2">Home Kit Description *</label>
                            <input type="text" name="jersey_home" id="jersey_home" value="{{ old('jersey_home') }}" placeholder="e.g. Red & White Stripes" required class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                            @error('jersey_home')
                            <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="jersey_away" class="block text-[10px] font-black uppercase text-zinc-400 mb-2">Away Kit Description *</label>
                            <input type="text" name="jersey_away" id="jersey_away" value="{{ old('jersey_away') }}" placeholder="e.g. Solid Blue" required class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                            @error('jersey_away')
                            <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Roster Section -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-zinc-400">Squad Roster (Min 11 Players)</h3>
                        <button type="button" @click="addPlayer()" class="bg-primary hover:bg-zinc-950 text-white font-black text-[10px] px-4 py-2 rounded-xl transition uppercase tracking-widest">
                            + Add Player
                        </button>
                    </div>

                    @error('players')
                    <div class="bg-rose-500/10 text-rose-600 px-4 py-3 rounded-xl font-bold text-xs mb-4">
                        {{ $message }}
                    </div>
                    @enderror

                    <!-- Roster List Grid -->
                    <div class="space-y-3">
                        <template x-for="(player, index) in players" :key="index">
                            <div class="flex flex-col md:flex-row gap-3 bg-zinc-50 border border-zinc-100 rounded-2xl p-4 items-end relative md:items-center">
                                
                                <div class="w-8 h-8 rounded-lg bg-zinc-200/50 flex items-center justify-center font-black text-zinc-400 text-xs shrink-0" x-text="index + 1"></div>
                                
                                <!-- Player Name -->
                                <div class="flex-1 w-full">
                                    <label class="block text-[9px] font-black uppercase text-zinc-400 mb-1 md:hidden">Full Name</label>
                                    <input type="text" :name="`players[${index}][name]`" x-model="player.name" required placeholder="Full Name" class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                </div>

                                <!-- Shirt Number -->
                                <div class="w-full md:w-24">
                                    <label class="block text-[9px] font-black uppercase text-zinc-400 mb-1 md:hidden">Jersey #</label>
                                    <input type="number" :name="`players[${index}][shirt_number]`" x-model="player.shirt_number" required min="1" max="99" placeholder="Jersey #" class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                </div>

                                <!-- Position -->
                                <div class="w-full md:w-36">
                                    <label class="block text-[9px] font-black uppercase text-zinc-400 mb-1 md:hidden">Position</label>
                                    <select :name="`players[${index}][position]`" x-model="player.position" required class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                        <option value="Goalkeeper">Goalkeeper</option>
                                        <option value="Defender">Defender</option>
                                        <option value="Midfielder">Midfielder</option>
                                        <option value="Forward">Forward</option>
                                    </select>
                                </div>

                                <!-- DOB -->
                                <div class="w-full md:w-36">
                                    <label class="block text-[9px] font-black uppercase text-zinc-400 mb-1 md:hidden">Date of Birth</label>
                                    <input type="date" :name="`players[${index}][dob]`" x-model="player.dob" required class="w-full bg-white border border-zinc-200 rounded-lg px-3 py-2 text-xs font-bold text-primary focus:border-secondary outline-none transition">
                                </div>

                                <!-- Identification -->
                                <div class="w-full md:w-44">
                                    <label class="block text-[9px] font-black uppercase text-zinc-400 mb-1 md:hidden">ID Document/Photo *</label>
                                    <input type="file" :name="`players[${index}][id_card]`" required accept="image/*,application/pdf" class="w-full bg-white border border-zinc-200 rounded-lg px-2 py-1.5 text-[10px] font-bold text-primary focus:border-secondary outline-none transition">
                                </div>

                                <!-- Action -->
                                <div class="shrink-0">
                                    <button type="button" @click="removePlayer(index)" :disabled="players.length <= 1" class="w-8 h-8 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-600 flex items-center justify-center font-bold text-xs disabled:opacity-50 disabled:cursor-not-allowed transition">
                                        ✕
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Min squad validation notice -->
                    <div x-show="players.length < 11" class="mt-4 bg-amber-500/10 text-amber-600 px-4 py-3 rounded-xl font-bold text-xs flex items-center gap-2">
                        <span>⚠</span> Roster requires at least 11 players to submit. Current size: <span x-text="players.length"></span>/11.
                    </div>
                </div>

                <button type="submit" :disabled="players.length < 11" class="w-full bg-primary text-white font-black py-4 rounded-2xl hover:bg-zinc-950 transition duration-300 flex items-center justify-center gap-2 text-sm uppercase tracking-wider disabled:opacity-50 disabled:cursor-not-allowed">
                    Lock Roster & Proceed to Pay
                </button>
            </form>
        </div>

        <!-- Sidebar pricing info -->
        <div class="space-y-6">
            <div class="bg-zinc-900 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <span class="text-[9px] font-black uppercase text-secondary tracking-widest">Order Summary</span>
                <div class="text-3xl font-black tracking-tight mt-2">
                    ₦{{ number_format(floatval($settings['registration_phase2_fee'] ?? 15000) * 0.60) }}
                </div>
                <p class="text-zinc-400 text-[11px] mt-2 leading-relaxed">
                    60% of the Phase 2 fee is due now upon registration. The remaining 40% must be paid before tournament kickoff.
                </p>
                <hr class="my-4 border-white/5">
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-zinc-500 font-medium">Total Phase 2 Fee</span>
                        <span class="font-bold">₦{{ number_format(floatval($settings['registration_phase2_fee'] ?? 15000)) }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-400 font-bold">
                        <span>Due Now (60%)</span>
                        <span>₦{{ number_format(floatval($settings['registration_phase2_fee'] ?? 15000) * 0.60) }}</span>
                    </div>
                    <div class="flex justify-between text-zinc-500">
                        <span>Due Before Kickoff (40%)</span>
                        <span>₦{{ number_format(floatval($settings['registration_phase2_fee'] ?? 15000) * 0.40) }}</span>
                    </div>
                    <hr class="border-white/5 my-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-400 font-bold">Pay Now</span>
                        <span class="text-secondary font-black">₦{{ number_format(floatval($settings['registration_phase2_fee'] ?? 15000) * 0.60) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-zinc-100">
                <h4 class="text-xs font-black uppercase text-primary mb-3">Phase 1 Summary</h4>
                <div class="space-y-2 text-[10px] text-zinc-500 font-semibold">
                    <div class="flex justify-between">
                        <span>Team name:</span>
                        <span class="text-primary">{{ $registration->team_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Contact:</span>
                        <span class="text-primary">{{ $registration->contact_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Code:</span>
                        <span class="text-primary font-mono select-all">{{ $registration->registration_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Paid Phase 1:</span>
                        <span class="text-emerald-600">₦{{ number_format($registration->phase1_amount) }} (✓ Paid)</span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
