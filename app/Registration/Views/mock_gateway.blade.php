<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paystack Checkout (Simulation Mode)</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-zinc-100 flex min-h-screen items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl border border-zinc-200/50 relative overflow-hidden">
        
        <!-- Paystack Branding Banner -->
        <div class="flex items-center justify-between mb-8 pb-6 border-b border-zinc-100">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-white font-black text-xs leading-none">
                    p
                </div>
                <span class="text-sm font-black tracking-tight text-zinc-800">paystack <span class="text-emerald-500 font-bold text-xs uppercase tracking-widest">Simulator</span></span>
            </div>
            <div class="bg-amber-100 text-amber-800 text-[8px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">
                Local Sandbox
            </div>
        </div>

        <!-- Checkout Details -->
        <div class="space-y-4 mb-8 text-center">
            <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest block">Paying to Tournament Portal</span>
            <div class="text-3xl font-black text-zinc-950 tracking-tight">
                ₦{{ number_format($amount) }}
            </div>
            
            <div class="bg-zinc-50 rounded-2xl p-4 border border-zinc-100 text-left text-xs font-semibold text-zinc-500 space-y-2 mt-4">
                <div class="flex justify-between">
                    <span>Account Email:</span>
                    <span class="text-zinc-900">{{ $email }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Payment Purpose:</span>
                    <span class="text-zinc-900">{{ $purpose }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Transaction Ref:</span>
                    <span class="text-zinc-900 font-mono text-[10px]">{{ $reference }}</span>
                </div>
            </div>
            
            <p class="text-[10px] text-amber-600 font-semibold text-left bg-amber-500/10 p-3 rounded-xl leading-relaxed">
                ⚠ **Notice:** This is a simulated checkout gateway because Paystack API keys are not configured in your environment variables. No real funds will be charged.
            </p>
        </div>

        <!-- Checkout Options -->
        <div class="space-y-3">
            <a href="{{ route('registration.callback', ['reference' => $reference]) }}" class="block w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black text-center py-4 rounded-2xl transition duration-200 text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20">
                Authorize Successful Payment
            </a>
            
            <a href="{{ route('registration.instructions') }}?error=Payment+declined+by+user." class="block w-full bg-zinc-100 hover:bg-zinc-200 text-zinc-500 text-center font-bold py-4 rounded-2xl transition text-xs uppercase tracking-widest">
                Decline & Cancel Payment
            </a>
        </div>

        <div class="mt-8 text-center text-[9px] font-bold text-zinc-400 uppercase tracking-wider">
            Secured by Paystack Sandbox Integration
        </div>
    </div>
</body>
</html>
