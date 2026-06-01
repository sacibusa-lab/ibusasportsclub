@extends('admin.layout')

@section('title', 'Competition Registrations')

@section('content')
<div class="space-y-8">

    <!-- Sub-Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-100 dark:border-zinc-800">
        <div>
            <h2 class="text-xl font-black text-primary dark:text-white uppercase tracking-tight">Enrollment Management</h2>
            <p class="text-zinc-400 text-xs font-semibold mt-1">Review team submissions, payment statuses, and roster uploads.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.registrations.settings') }}" class="bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-primary dark:text-zinc-200 font-bold px-5 py-3 rounded-xl text-xs uppercase tracking-wider transition">
                Registration Settings
            </a>
        </div>
    </div>

    <!-- Filters and Table Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-sm border border-zinc-100 dark:border-zinc-800">
        
        <!-- Search and Filter Form -->
        <form action="{{ route('admin.registrations.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by team, code, contact name, email..." class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-xs font-bold text-primary dark:text-white focus:border-secondary outline-none transition">
            </div>
            <div>
                <select name="status" class="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-xs font-bold text-primary dark:text-white focus:border-secondary outline-none transition">
                    <option value="">All Statuses</option>
                    <option value="initiated" {{ request('status') == 'initiated' ? 'selected' : '' }}>Initiated (Unpaid)</option>
                    <option value="phase1_paid" {{ request('status') == 'phase1_paid' ? 'selected' : '' }}>Phase 1 Paid</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed (Fully Paid)</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-primary text-white font-black rounded-xl hover:bg-zinc-950 transition duration-200 text-xs uppercase tracking-widest">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.registrations.index') }}" class="bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 p-3 rounded-xl hover:bg-zinc-200 transition text-xs flex items-center justify-center font-bold">
                    Clear
                </a>
                @endif
            </div>
        </form>

        <!-- Registrations Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800 text-zinc-400 uppercase font-black tracking-wider text-[10px]">
                        <th class="py-4 px-2">Team & Competition</th>
                        <th class="py-4 px-2">Contact Person</th>
                        <th class="py-4 px-2">Code</th>
                        <th class="py-4 px-2">Status</th>
                        <th class="py-4 px-2 text-right">Phase 1</th>
                        <th class="py-4 px-2 text-right">Phase 2</th>
                        <th class="py-4 px-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 text-zinc-600 dark:text-zinc-300 font-medium">
                    @forelse($registrations as $reg)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition">
                        <td class="py-4 px-2">
                            <span class="block font-black text-primary dark:text-white uppercase">{{ $reg->team_name }}</span>
                            <span class="block text-[10px] text-zinc-400 font-semibold uppercase mt-0.5">{{ $reg->competition->name ?? 'N/A' }}</span>
                        </td>
                        <td class="py-4 px-2">
                            <span class="block text-primary dark:text-zinc-200 font-bold">{{ $reg->contact_name }}</span>
                            <span class="block text-[10px] text-zinc-400 mt-0.5">{{ $reg->contact_phone }} | {{ $reg->contact_email }}</span>
                        </td>
                        <td class="py-4 px-2">
                            @if($reg->registration_code)
                            <span class="font-mono text-zinc-900 dark:text-zinc-100 font-bold bg-zinc-100 dark:bg-zinc-800 px-2.5 py-1 rounded-lg uppercase select-all">{{ $reg->registration_code }}</span>
                            @else
                            <span class="text-zinc-400 font-semibold italic">N/A</span>
                            @endif
                        </td>
                        <td class="py-4 px-2">
                            @if($reg->status === 'completed')
                            <span class="inline-flex items-center gap-1.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-black px-2.5 py-1 rounded-full uppercase text-[9px]">Fully Registered</span>
                            @elseif($reg->status === 'phase1_paid')
                            <span class="inline-flex items-center gap-1.5 bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 font-black px-2.5 py-1 rounded-full uppercase text-[9px]">Phase 1 Paid</span>
                            @else
                            <span class="inline-flex items-center gap-1.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 font-black px-2.5 py-1 rounded-full uppercase text-[9px]">Initiated</span>
                            @endif
                        </td>
                        <td class="py-4 px-2 text-right">
                            <span class="block font-black text-primary dark:text-zinc-200">₦{{ number_format($reg->phase1_amount) }}</span>
                            <span class="block text-[9px] uppercase font-bold {{ $reg->phase1_payment_status === 'paid' ? 'text-emerald-500' : 'text-zinc-400' }}">
                                {{ $reg->phase1_payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                            </span>
                        </td>
                        <td class="py-4 px-2 text-right">
                            <span class="block font-black text-primary dark:text-zinc-200">₦{{ number_format($reg->phase2_amount) }}</span>
                            <span class="block text-[9px] uppercase font-bold {{ $reg->phase2_payment_status === 'paid' ? 'text-emerald-500' : 'text-zinc-400' }}">
                                {{ $reg->phase2_payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                            </span>
                        </td>
                        <td class="py-4 px-2 text-right">
                            <div class="flex justify-end gap-1.5">
                                <a href="{{ route('admin.registrations.show', $reg->id) }}" class="bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-primary dark:text-zinc-200 p-2.5 rounded-lg transition" title="View details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <form action="{{ route('admin.registrations.destroy', $reg->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this registration record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white p-2.5 rounded-lg transition" title="Delete record">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-zinc-400 font-bold italic">
                            No registration records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
        <div class="mt-8 border-t border-zinc-100 dark:border-zinc-800 pt-6">
            {{ $registrations->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
