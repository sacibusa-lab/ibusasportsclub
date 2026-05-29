@extends('admin.layout')

@section('title', 'Prediction History')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.predictor.index') }}" class="text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 hover:text-primary dark:text-white transition text-[10px] font-black uppercase tracking-widest flex items-center gap-2 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Users
            </a>
            <h2 class="text-2xl font-black text-primary dark:text-white uppercase italic tracking-tighter">Predictions: {{ $user->name }}</h2>
            <div class="flex flex-wrap gap-4 mt-1">
                <span class="text-[10px] font-bold text-secondary uppercase tracking-widest">{{ $user->predictor_points }} TOTAL PTS</span>
                <span class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-l border-zinc-200 dark:border-zinc-700 pl-4">PHONE: {{ $user->phone ?? 'N/A' }}</span>
                <span class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest border-l border-zinc-200 dark:border-zinc-700 pl-4">IP: {{ $user->registration_ip }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-100 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800/50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Match</th>
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest text-center">Prediction</th>
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest text-center">Actual Result</th>
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest text-right">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @forelse($predictions as $prediction)
                    <tr class="hover:bg-zinc-50 dark:bg-zinc-800/50/50 transition duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] font-black text-primary dark:text-white uppercase tracking-tighter">{{ $prediction->match->homeTeam->name }} vs {{ $prediction->match->awayTeam->name }}</span>
                                <span class="text-[9px] text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 font-medium">{{ $prediction->match->match_date->format('j M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-zinc-100 dark:bg-zinc-800 text-primary dark:text-white px-3 py-1 rounded-lg font-black text-sm">
                                {{ $prediction->home_score }} - {{ $prediction->away_score }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($prediction->match->status === 'finished')
                            <span class="bg-secondary/10 text-primary dark:text-white px-3 py-1 rounded-lg font-black text-sm italic">
                                {{ $prediction->match->home_score }} - {{ $prediction->match->away_score }}
                            </span>
                            @else
                            <span class="text-[9px] font-black text-zinc-300 dark:text-zinc-500 uppercase tracking-widest">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($prediction->is_processed)
                            <span class="text-[9px] font-black text-emerald-500 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-widest">Processed</span>
                            @else
                            <span class="text-[9px] font-black text-amber-500 bg-amber-50 px-3 py-1 rounded-full uppercase tracking-widest">Waiting</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-sm {{ $prediction->points_earned > 0 ? 'text-primary' : 'text-zinc-300 dark:text-zinc-500' }}">
                                +{{ $prediction->points_earned }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <span class="text-zinc-300 dark:text-zinc-500 font-black uppercase tracking-widest text-[10px]">No predictions made yet.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
