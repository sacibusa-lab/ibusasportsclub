@extends('admin.layout')

@section('title', 'Predictor League Users')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-primary uppercase italic tracking-tighter">Predictor League Users</h2>
            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-1">Manage tipsters and view their predictions</p>
        </div>
    </div>

    <!-- Prediction Heatmap / Trending Matches -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-[2.5rem] border border-zinc-100 p-8 shadow-sm">
            <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                <span class="w-1.5 h-4 bg-orange-500 rounded-full animate-pulse"></span>
                Hot Engagement (Predictions Count)
            </h3>
            <div class="space-y-6">
                @forelse($hotMatches as $match)
                <div class="space-y-2 group">
                    <div class="flex justify-between items-end">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-1.5">
                                <img src="{{ $match->homeTeam->logo_url }}" class="w-6 h-6 object-contain">
                                <img src="{{ $match->awayTeam->logo_url }}" class="w-6 h-6 object-contain">
                            </div>
                            <span class="text-[10px] font-black text-primary uppercase tracking-tighter truncate max-w-[150px]">{{ $match->homeTeam->name }} v {{ $match->awayTeam->name }}</span>
                        </div>
                        <span class="text-[10px] font-black text-orange-500 uppercase">{{ $match->predictions_count }} Tips</span>
                    </div>
                    <div class="h-1.5 w-full bg-zinc-50 rounded-full overflow-hidden">
                        @php
                            $maxPreds = $hotMatches->max('predictions_count') ?: 1;
                            $percentage = ($match->predictions_count / $maxPreds) * 100;
                        @endphp
                        <div class="h-full bg-orange-500 rounded-full transition-all duration-1000 group-hover:bg-orange-600" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest italic py-4">No active predictions found</p>
                @endforelse
            </div>
        </div>

        <div class="bg-primary rounded-[2.5rem] p-8 shadow-lg relative overflow-hidden flex flex-col justify-center">
            <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <h3 class="text-white font-black text-xl italic uppercase tracking-tighter">Intelligence Hub</h3>
                <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest mt-1">Live Predictor Performance</p>
                
                <div class="mt-8 grid grid-cols-2 gap-6">
                    <div>
                        <span class="text-[8px] font-black text-secondary uppercase tracking-[0.2em]">Active Fans</span>
                        <p class="text-3xl font-black text-white italic tracking-tighter">{{ number_format($users->total()) }}</p>
                    </div>
                    <div>
                        <span class="text-[8px] font-black text-secondary uppercase tracking-[0.2em]">Total Tipped</span>
                        <p class="text-3xl font-black text-white italic tracking-tighter">{{ number_format(\App\Models\Prediction::count()) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Rank</th>
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">User</th>
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Device / IP</th>
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-center">Points</th>
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @foreach($users as $index => $user)
                    <tr class="hover:bg-zinc-50/50 transition duration-200">
                        <td class="px-6 py-4">
                            <span class="font-black italic {{ $index < 3 ? 'text-secondary text-lg' : 'text-zinc-300' }}">#{{ $users->firstItem() + $index }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-primary">{{ $user->name }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-zinc-400 font-medium">{{ $user->email }}</span>
                                    <span class="text-[10px] text-zinc-300">•</span>
                                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-tighter">{{ $user->phone ?? 'No Phone' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-zinc-500 uppercase tracking-tighter">IP: {{ $user->registration_ip ?? 'N/A' }}</span>
                                <span class="text-[9px] text-zinc-400 font-medium truncate max-w-[150px]">Token: {{ $user->device_token ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block bg-primary text-secondary px-3 py-1 rounded-full font-black text-xs min-w-[40px]">
                                {{ $user->predictor_points }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.predictor.show', $user->id) }}" class="inline-flex items-center gap-2 bg-zinc-100 text-zinc-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition shadow-sm">
                                View Predictions
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 bg-zinc-50/30 border-t border-zinc-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
