<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join Predictor League | {{ $siteSettings['site_name'] }}</title>
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $siteSettings['primary_color'] }}',
                        secondary: '{{ $siteSettings['secondary_color'] }}',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .mesh-bg {
            background-color: #0b0b0b;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,20%,1) 0, transparent 40%),
                radial-gradient(at 100% 0%, hsla(339,49%,20%,1) 0, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="mesh-bg flex flex-col items-center justify-center min-h-screen p-6" x-data="{ 
    name: '{{ old('name', '') }}', 
    phone: '{{ old('phone', '') }}',
    showPass: false,
    showConfirm: false
}">
    <div class="w-full max-w-md pt-10 pb-20">
        <!-- Live Preview "Fan Card" -->
        <div class="mb-12 animate-float">
            <div class="glass-card rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden group">
                <!-- Card Branding -->
                <div class="flex justify-between items-start mb-8">
                    <div class="w-12 h-14 bg-primary flex items-center justify-center rounded-lg border border-white/10 shadow-lg">
                        <span class="text-secondary font-black text-xs italic">{{ $siteSettings['site_short_name'] }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[8px] font-black text-secondary uppercase tracking-[0.3em]">Official Member</span>
                        <div class="text-[10px] font-black text-white uppercase tracking-widest mt-0.5">Predictor Hub</div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="space-y-4">
                    <div class="space-y-0.5">
                        <span class="text-[8px] font-black text-zinc-500 uppercase tracking-widest">Full Name</span>
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter truncate" x-text="name || 'YOUR NAME HERE'"></h3>
                    </div>
                    
                    <div class="flex items-end justify-between">
                        <div class="space-y-0.5">
                            <span class="text-[8px] font-black text-zinc-500 uppercase tracking-widest">Verified Phone</span>
                            <p class="text-[10px] font-black text-secondary tracking-[0.2em]" x-text="phone || '+234 --- --- ----'"></p>
                        </div>
                        <div class="w-10 h-10 border border-white/5 bg-white/5 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-secondary/30" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Card Decoration -->
                <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-primary/20 rounded-full blur-3xl group-hover:bg-primary/30 transition duration-700"></div>
            </div>
        </div>

        <!-- Register Card -->
        <div class="bg-white/[0.02] backdrop-blur-3xl rounded-[3rem] border border-white/10 p-10 shadow-2xl relative">
            <div class="mb-10">
                <h2 class="text-2xl font-black text-white uppercase tracking-tighter italic border-l-4 border-secondary pl-4">Join the League</h2>
                <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-2 ml-5">Create your profile to start tipping</p>
            </div>
            
            <form action="{{ route('register') }}" method="POST" id="registrationForm" class="space-y-6">
                @csrf
                <input type="hidden" name="device_token" id="device_token">
                
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] px-1">Full Name</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-zinc-500 group-focus-within:text-secondary mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" name="name" x-model="name" required autofocus
                            placeholder="John Doe"
                            class="w-full bg-white/[0.03] border border-white/5 p-4 pl-12 rounded-2xl font-bold text-white placeholder:text-zinc-700 focus:ring-2 focus:ring-secondary focus:bg-white/5 focus:border-transparent outline-none transition text-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] px-1">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-zinc-500 group-focus-within:text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="john@example.com"
                            class="w-full bg-white/[0.03] border border-white/5 p-4 pl-12 rounded-2xl font-bold text-white placeholder:text-zinc-700 focus:ring-2 focus:ring-secondary focus:bg-white/5 focus:border-transparent outline-none transition text-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] px-1">Phone Number</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-zinc-500 group-focus-within:text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <input type="tel" name="phone" x-model="phone" required
                            placeholder="+234..."
                            class="w-full bg-white/[0.03] border border-white/5 p-4 pl-12 rounded-2xl font-bold text-white placeholder:text-zinc-700 focus:ring-2 focus:ring-secondary focus:bg-white/5 focus:border-transparent outline-none transition text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] px-1">Password</label>
                        <div class="relative group">
                            <input :type="showPass ? 'text' : 'password'" name="password" required
                                class="w-full bg-white/[0.03] border border-white/5 p-4 rounded-2xl font-bold text-white focus:ring-2 focus:ring-secondary focus:bg-white/5 focus:border-transparent outline-none transition text-sm">
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <svg x-show="!showPass" class="h-4 w-4 text-zinc-600 hover:text-secondary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPass" class="h-4 w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.04m4.066-1.56a10.048 10.048 0 012.313-.468M15.312 4.125A9.957 9.957 0 0112 4a10.015 10.015 0 012.23.248m-1.238 1.238a3 3 0 11-4.243 4.243m4.242-4.243L8 16m2-8l-4 4"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-[0.2em] px-1">Confirm</label>
                        <div class="relative group">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                                class="w-full bg-white/[0.03] border border-white/5 p-4 rounded-2xl font-bold text-white focus:ring-2 focus:ring-secondary focus:bg-white/5 focus:border-transparent outline-none transition text-sm">
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <svg x-show="!showConfirm" class="h-4 w-4 text-zinc-600 hover:text-secondary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showConfirm" class="h-4 w-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.04m4.066-1.56a10.048 10.048 0 012.313-.468M15.312 4.125A9.957 9.957 0 0112 4a10.015 10.015 0 012.23.248m-1.238 1.238a3 3 0 11-4.243 4.243m4.242-4.243L8 16m2-8l-4 4"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-500 p-4 rounded-2xl text-[10px] font-bold text-center uppercase tracking-widest">
                    {{ $errors->first() }}
                </div>
                @endif

                <button type="submit" 
                    id="submitBtn"
                    class="w-full bg-secondary text-primary font-black py-5 rounded-2xl hover:bg-white transition-all duration-500 uppercase tracking-[0.2em] text-[10px] shadow-[0_20px_40px_-15px_rgba(0,0,0,0.5)] flex items-center justify-center gap-3 mt-4 hover:-translate-y-1">
                    <span id="btnText">Connect & Verify Card</span>
                    <svg class="w-4 h-4 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-secondary hover:underline ml-1">Log In</a>
                </p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-[10px] font-black text-zinc-500 hover:text-white transition uppercase tracking-widest flex items-center justify-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Public Site
            </a>
        </div>
    </div>

    <script>
        // Device Token Generation Logic
        document.addEventListener('DOMContentLoaded', function() {
            let deviceToken = localStorage.getItem('predictor_device_token');
            
            if (!deviceToken) {
                // Generate a pseudo-random unique token
                deviceToken = 'DT-' + Math.random().toString(36).substr(2, 9) + '-' + Date.now().toString(36);
                localStorage.setItem('predictor_device_token', deviceToken);
            }
            
            document.getElementById('device_token').value = deviceToken;

            // Simple loading state
            const form = document.getElementById('registrationForm');
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');

            form.addEventListener('submit', function() {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btnText.innerText = 'Syncing...';
            });
        });
    </script>
</body>
</html>
