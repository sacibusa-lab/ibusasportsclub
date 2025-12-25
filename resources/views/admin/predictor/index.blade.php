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
