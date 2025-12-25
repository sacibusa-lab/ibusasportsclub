@extends('admin.layout')

@section('title', 'Manage Groups')

@section('content')
<div x-data="{ editModal: false, activeGroup: { id: '', name: '', competition_id: '' } }" class="space-y-8">
    <div class="grid lg:grid-cols-3 gap-12">
        <!-- Add Group Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100 italic">
                <h3 class="text-xl font-black mb-6 text-primary uppercase tracking-tighter">Create New Group</h3>
                <form action="{{ route('admin.groups.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Group Name</label>
                        <input type="text" name="name" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" placeholder="e.g. Group A" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Assign to Competition</label>
                        <select name="competition_id" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none appearance-none uppercase" required>
                            @foreach($competitions as $competition)
                            <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-primary text-secondary font-black p-4 rounded-2xl hover:scale-[1.02] transition shadow-lg mt-4 uppercase tracking-widest text-[10px]">Create Group</button>
                </form>
            </div>
        </div>

        <!-- Groups List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-50 text-zinc-400 text-[10px] font-black uppercase tracking-widest border-b border-zinc-100">
                            <th class="px-6 py-4">Group Name</th>
                            <th class="px-6 py-4">Competition</th>
                            <th class="px-6 py-4">Teams</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($groups as $group)
                        <tr class="hover:bg-zinc-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="font-extrabold text-primary text-sm uppercase tracking-tighter">{{ $group->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">{{ $group->competition->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-zinc-500 text-[10px] font-black uppercase italic">{{ $group->teams->count() }} Teams</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 text-zinc-300">
                                    <button @click="activeGroup = { id: {{ $group->id }}, name: '{{ addslashes($group->name) }}', competition_id: '{{ $group->competition_id }}' }; editModal = true" class="p-2 hover:text-primary transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.groups.destroy', $group->id) }}" method="POST" onsubmit="return confirm('Delete this group? Teams will lose their group association.')">
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
    </div>

    <!-- Edit Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm">
        <div @click.away="editModal = false" class="bg-white rounded-3xl p-8 shadow-2xl border border-zinc-100 w-full max-w-md animate-scale-in">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tighter">Edit Group</h3>
                <button @click="editModal = false" class="text-zinc-400 hover:text-primary transition">✕</button>
            </div>
            <form :action="'{{ url('admin/groups') }}/' + activeGroup.id" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Group Name</label>
                    <input type="text" name="name" x-model="activeGroup.name" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Competition</label>
                    <select name="competition_id" x-model="activeGroup.competition_id" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none appearance-none uppercase" required>
                        @foreach($competitions as $competition)
                        <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" @click="editModal = false" class="flex-1 bg-zinc-100 text-zinc-500 font-black p-4 rounded-2xl hover:bg-zinc-200 transition uppercase tracking-widest text-[10px]">Cancel</button>
                    <button type="submit" class="flex-1 bg-primary text-secondary font-black p-4 rounded-2xl hover:scale-[1.02] transition shadow-lg uppercase tracking-widest text-[10px]">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-scale-in { animation: scale-in 0.2s ease-out; }
</style>
@endsection
