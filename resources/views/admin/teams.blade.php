@extends('admin.layout')

@section('title', 'Team Management')

@section('content')
<div x-data="{ 
    editModal: false, 
    playerModal: false,
    editPlayerModal: false,
    editTeam: { id: '', name: '', manager: '', stadium_name: '', primary_color: '', group_id: '', logo_url: '' },
    newPlayer: { team_id: '', team_name: '' },
    editPlayer: { id: '', name: '', shirt_number: '', position: 'FWD', team_id: '', team_name: '', image_url: '', full_image_url: '' },
    openEdit(team) {
        this.editTeam = { 
            id: team.id, 
            name: team.name, 
            manager: team.manager || '', 
            stadium_name: team.stadium_name || '', 
            primary_color: team.primary_color || '#000000',
            group_id: team.group_id,
            logo_url: team.logo_url
        };
        this.editModal = true;
    },
    openAddPlayer(id, name) {
        this.newPlayer = { team_id: id, team_name: name };
        this.playerModal = true;
    },
    openEditPlayer(player, teamName) {
        this.editPlayer = { 
            id: player.id,
            name: player.name,
            shirt_number: player.shirt_number,
            position: player.position,
            team_id: player.team_id,
            team_name: teamName,
            image_url: player.image_url,
            full_image_url: player.full_image_url
        };
        this.editPlayerModal = true;
    }
}">
    <div class="grid lg:grid-cols-3 gap-12">
        <!-- Add Team Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100 sticky top-28">
                <h3 class="text-xl font-black mb-6 text-primary uppercase italic tracking-tighter">Add New Team</h3>
                <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @if ($errors->any())
                        <div class="p-4 bg-rose-50 text-rose-500 rounded-2xl text-[10px] font-bold uppercase tracking-widest border border-rose-100">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
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
                    <div class="bg-zinc-50 rounded-2xl border border-transparent hover:border-zinc-200 transition overflow-hidden">
                        <div class="p-4 flex items-center justify-between border-b border-zinc-100 bg-white/50">
                            <div class="flex items-center gap-3">
                                @if($team->logo_url)
                                <img src="{{ $team->logo_url }}" class="w-8 h-8 object-contain">
                                @else
                                <div class="w-8 h-8 bg-zinc-200 rounded-full flex items-center justify-center font-black text-zinc-400 text-[10px] uppercase">
                                    {{ substr($team->name, 0, 1) }}
                                </div>
                                @endif
                                <div class="flex flex-col">
                                    <span class="font-bold text-primary">{{ $team->name }}</span>
                                    <span class="text-[8px] font-black text-zinc-400 uppercase tracking-widest">{{ $team->players->count() }} Players</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button title="Add Player" @click="openAddPlayer({{ $team->id }}, '{{ addslashes($team->name) }}')" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg text-emerald-500 hover:bg-emerald-500 hover:text-white transition shadow-sm border border-zinc-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </button>
                                <button title="Edit Team" @click="openEdit({ id: {{ $team->id }}, name: '{{ addslashes($team->name) }}', manager: '{{ addslashes($team->manager) }}', stadium_name: '{{ addslashes($team->stadium_name) }}', primary_color: '{{ $team->primary_color }}', group_id: {{ $team->group_id }}, logo_url: '{{ $team->logo_url }}' })" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg text-zinc-400 hover:text-primary transition shadow-sm border border-zinc-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Remove this team?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Team" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg text-zinc-400 hover:text-red-500 transition shadow-sm border border-zinc-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- Player Mini-List -->
                        <div class="px-4 py-3 space-y-2 max-h-48 overflow-y-auto no-scrollbar">
                            @forelse($team->players as $player)
                            <div class="flex items-center justify-between bg-white/30 p-2 rounded-xl group hover:bg-white transition">
                                <div class="flex items-center gap-2">
                                    @if($player->image_url)
                                    <img src="{{ $player->image_url }}" class="w-6 h-6 rounded-full object-cover">
                                    @else
                                    <div class="w-6 h-6 bg-zinc-200 rounded-full flex items-center justify-center font-black text-zinc-400 text-[8px] uppercase">
                                        {{ substr($player->name, 0, 1) }}
                                    </div>
                                    @endif
                                    <span class="text-[10px] font-bold text-zinc-600 group-hover:text-primary transition">{{ $player->name }}</span>
                                    <span class="text-[8px] font-black text-zinc-300">#{{ $player->shirt_number }} {{ $player->position }}</span>
                                </div>
                                <div class="flex items-center opacity-0 group-hover:opacity-100 transition">
                                    <button @click="openEditPlayer({ id: {{ $player->id }}, name: '{{ addslashes($player->name) }}', shirt_number: {{ $player->shirt_number ?? 'null' }}, position: '{{ $player->position }}', team_id: {{ $player->team_id }}, image_url: '{{ $player->image_url }}', full_image_url: '{{ $player->full_image_url }}' }, '{{ addslashes($team->name) }}')" class="p-1 text-zinc-300 hover:text-primary"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                                    <form action="{{ route('admin.players.destroy', $player->id) }}" method="POST" onsubmit="return confirm('Remove player?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1 text-zinc-300 hover:text-red-500"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <p class="text-[9px] text-zinc-300 italic py-2">No players yet.</p>
                            @endforelse
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

    <!-- Add Player Modal -->
    <div x-show="playerModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm">
        <div @click.away="playerModal = false" class="bg-white rounded-3xl p-8 shadow-2xl border border-zinc-100 w-full max-w-md animate-scale-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-black text-primary uppercase italic tracking-tighter leading-none">Add Player</h3>
                    <p class="text-[10px] font-black text-zinc-300 uppercase tracking-widest mt-2" x-text="'Joining ' + newPlayer.team_name"></p>
                </div>
                <button @click="playerModal = false" class="text-zinc-400 hover:text-primary transition">✕</button>
            </div>
            <form action="{{ route('admin.players.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @if ($errors->any())
                    <div class="p-4 bg-rose-50 text-rose-500 rounded-2xl text-[10px] font-bold uppercase tracking-widest border border-rose-100">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <input type="hidden" name="team_id" :value="newPlayer.team_id">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="name" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" placeholder="e.g. ERLING HAALAND" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Shirt #</label>
                        <input type="number" name="shirt_number" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" placeholder="9" min="1" max="99">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Position</label>
                    <select name="position" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                        <option value="GK">Goalkeeper (GK)</option>
                        <option value="DEF">Defender (DEF)</option>
                        <option value="MID">Midfielder (MID)</option>
                        <option value="FWD" selected>Forward (FWD)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Avatar</label>
                        <input type="file" name="image" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-[10px]" accept="image/*">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Full Photo</label>
                        <input type="file" name="full_image" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-[10px]" accept="image/*">
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary text-secondary font-black py-4 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-[10px] shadow-lg mt-4">Register Player</button>
            </form>
        </div>
    </div>

    <!-- Edit Player Modal -->
    <div x-show="editPlayerModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm">
        <div @click.away="editPlayerModal = false" class="bg-white rounded-3xl p-8 shadow-2xl border border-zinc-100 w-full max-w-md animate-scale-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-black text-primary uppercase italic tracking-tighter leading-none">Edit Player</h3>
                    <p class="text-[10px] font-black text-zinc-300 uppercase tracking-widest mt-2" x-text="'Member of ' + editPlayer.team_name"></p>
                </div>
                <button @click="editPlayerModal = false" class="text-zinc-400 hover:text-primary transition">✕</button>
            </div>
            <form :action="'{{ url('admin/players') }}/' + editPlayer.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                @if ($errors->any())
                    <div class="p-4 bg-rose-50 text-rose-500 rounded-2xl text-[10px] font-bold uppercase tracking-widest border border-rose-100">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <input type="hidden" name="team_id" :value="editPlayer.team_id">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="name" x-model="editPlayer.name" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Shirt #</label>
                        <input type="number" name="shirt_number" x-model="editPlayer.shirt_number" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" min="1" max="99">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Position</label>
                    <select name="position" x-model="editPlayer.position" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                        <option value="GK">Goalkeeper (GK)</option>
                        <option value="DEF">Defender (DEF)</option>
                        <option value="MID">Midfielder (MID)</option>
                        <option value="FWD">Forward (FWD)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Update Avatar</label>
                        <div class="flex items-center gap-2 mb-1" x-show="editPlayer.image_url">
                            <img :src="editPlayer.image_url" class="w-8 h-8 rounded-full object-cover border border-zinc-100">
                        </div>
                        <input type="file" name="image" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-[10px]" accept="image/*">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Update Full Photo</label>
                        <div class="flex items-center gap-2 mb-1" x-show="editPlayer.full_image_url">
                            <img :src="editPlayer.full_image_url" class="w-8 h-8 rounded-lg object-cover border border-zinc-100">
                        </div>
                        <input type="file" name="full_image" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-[10px]" accept="image/*">
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary text-secondary font-black py-4 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-[10px] shadow-lg mt-4">Save Changes</button>
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
