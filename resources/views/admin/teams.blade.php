@extends('admin.layout')

@section('title', 'Team Management')

@section('content')
<div x-data="{ 
    editModal: false, 
    editTeam: { id: '', name: '', manager: '', stadium_name: '', primary_color: '', group_id: '', logo_url: '' },
    openEdit(team) {
        this.editTeam = { ...team, manager: team.manager || '', stadium_name: team.stadium_name || '', primary_color: team.primary_color || '#000000' };
        this.editModal = true;
    }
}">
    <div class="grid lg:grid-cols-3 gap-12">
        <!-- Add Team Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100 sticky top-28">
                <h3 class="text-xl font-black mb-6 text-primary uppercase italic tracking-tighter">Add New Team</h3>
                <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Team Name</label>
                        <input type="text" name="name" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" placeholder="e.g. AFC Community" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Team Manager</label>
                        <input type="text" name="manager" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" placeholder="e.g. Coach Carter">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Stadium Name</label>
                        <input type="text" name="stadium_name" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" placeholder="e.g. Allianz Arena">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Primary Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="primary_color" class="h-12 w-12 rounded-xl cursor-pointer border-none bg-transparent" value="#000000">
                            <span class="text-xs font-bold text-zinc-400">Select Club Color</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Assign Group</label>
                        <select name="group_id" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none appearance-none uppercase" required>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Team Badge / Logo</label>
                        <input type="file" name="logo" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none text-xs" accept="image/*">
                    </div>
                    <button type="submit" class="w-full bg-primary text-white font-black p-4 rounded-2xl hover:scale-[1.02] transition shadow-lg mt-4 uppercase tracking-widest text-[10px]">Add Participant</button>
                </form>
            </div>
        </div>

        <!-- Teams List -->
        <div class="lg:col-span-2 space-y-8">
            @foreach($groups as $group)
            <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-8">
                <h4 class="text-zinc-400 font-black uppercase tracking-widest text-xs mb-6">{{ $group->name }} Participants</h4>
                <div class="grid grid-cols-2 gap-4">
                    @forelse($group->teams as $team)
                    <div class="bg-zinc-50 p-4 rounded-2xl flex items-center justify-between border border-transparent hover:border-zinc-200 transition">
                        <div class="flex items-center gap-3">
                            @if($team->logo_url)
                            <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                            @else
                            <div class="w-8 h-8 bg-zinc-200 rounded-full flex items-center justify-center font-black text-zinc-400 text-[10px] uppercase">
                                {{ substr($team->name, 0, 1) }}
                            </div>
                            @endif
                            <span class="font-bold text-primary">{{ $team->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click='openEdit(@json($team))' class="text-zinc-300 hover:text-primary transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Remove this team?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-zinc-300 hover:text-red-500 transition">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="col-span-2 text-zinc-300 text-sm font-bold lowercase tracking-widest italic">Empty group.</p>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm">
        <div @click.away="editModal = false" class="bg-white rounded-3xl p-8 shadow-2xl border border-zinc-100 w-full max-w-md animate-scale-in">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tighter">Edit Team</h3>
                <button @click="editModal = false" class="text-zinc-400 hover:text-primary transition">✕</button>
            </div>
            <form :action="'{{ url('admin/teams') }}/' + editTeam.id" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Team Name</label>
                    <input type="text" name="name" x-model="editTeam.name" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Team Manager</label>
                    <input type="text" name="manager" x-model="editTeam.manager" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Stadium Name</label>
                    <input type="text" name="stadium_name" x-model="editTeam.stadium_name" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Primary Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="primary_color" x-model="editTeam.primary_color" class="h-12 w-12 rounded-xl cursor-pointer border-none bg-transparent">
                        <span class="text-xs font-bold text-zinc-400">Select Club Color</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Assign Group</label>
                    <select name="group_id" x-model="editTeam.group_id" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none appearance-none uppercase" required>
                        @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Update Badge / Logo</label>
                    <div class="flex items-center gap-4 mb-2" x-show="editTeam.logo_url">
                        <img :src="editTeam.logo_url" class="w-12 h-12 object-contain bg-zinc-50 rounded-lg p-2">
                        <span class="text-[10px] font-bold text-zinc-400 italic">Current Logo</span>
                    </div>
                    <input type="file" name="logo" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none text-xs" accept="image/*">
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" @click="editModal = false" class="flex-1 bg-zinc-100 text-zinc-500 font-black p-4 rounded-2xl hover:bg-zinc-200 transition uppercase tracking-widest text-[10px]">Cancel</button>
                    <button type="submit" class="flex-1 bg-primary text-white font-black p-4 rounded-2xl hover:scale-[1.02] transition shadow-lg uppercase tracking-widest text-[10px]">Save Changes</button>
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
