@extends('admin.layout')

@section('title', 'Manage Competitions')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-primary italic uppercase tracking-tighter">Current Competitions</h2>
        <a href="{{ route('admin.competitions.create') }}" class="bg-secondary text-primary font-black px-6 py-3 rounded-xl hover:bg-white border-2 border-secondary transition text-xs uppercase tracking-widest flex items-center gap-2 shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Competition
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-zinc-50 text-zinc-400 text-[10px] font-black uppercase tracking-widest border-b border-zinc-100">
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Created At</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @foreach($competitions as $competition)
                <tr class="hover:bg-zinc-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-extrabold text-primary text-sm uppercase tracking-tighter">{{ $competition->name }}</span>
                            <span class="text-zinc-400 font-bold text-[9px] uppercase tracking-widest italic">{{ $competition->slug }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $competition->type == 'league' ? 'bg-indigo-100 text-indigo-600' : ($competition->type == 'knockout' ? 'bg-amber-100 text-amber-600' : 'bg-rose-100 text-rose-600') }}">
                            {{ $competition->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full {{ $competition->is_active ? 'bg-secondary' : 'bg-zinc-300' }}"></span>
                            <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">{{ $competition->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-zinc-500 text-[10px] font-black uppercase italic">{{ $competition->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2 text-zinc-300">
                            <a href="{{ route('admin.competitions.edit', $competition->id) }}" class="p-2 hover:text-primary transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.competitions.destroy', $competition->id) }}" method="POST" onsubmit="return confirm('Delete this competition? Matches and groups will also be affected.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 hover:text-accent transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
