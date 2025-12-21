@extends('admin.layout')

@section('title', 'Manage Players')

@section('content')
<div x-data="{ 
    editModal: false, 
    editPlayer: { id: '', name: '', team_id: '', position: '', shirt_number: '', image_url: '', full_image_url: '' },
    openEdit(player) {
        this.editPlayer = { ...player };
        this.editModal = true;
    }
}">
    <div class="grid lg:grid-cols-3 gap-12">
        <!-- Add Player Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100 sticky top-8">
                <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6 border-b border-zinc-50 pb-4">Register New Player</h3>
                <form action="{{ route('admin.players.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Full Name</label>
                            <input type="text" name="name" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" placeholder="e.g. ERLING HAALAND" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Shirt #</label>
                            <input type="number" name="shirt_number" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" placeholder="9" min="1" max="99">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Current Team</label>
                        <select name="team_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                            <option value="">Select Team</option>
                            @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Position</label>
                        <select name="position" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                            <option value="GK">Goalkeeper (GK)</option>
                            <option value="DEF">Defender (DEF)</option>
                            <option value="MID">Midfielder (MID)</option>
                            <option value="FWD">Forward (FWD)</option>
                            <option value="CF" selected>Center Forward (CF)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Avatar/Logo</label>
                            <input type="file" name="image" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" accept="image/*">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Full Photo (Cutout)</label>
                            <input type="file" name="full_image" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" accept="image/*">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-secondary font-black py-4 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-[10px] shadow-lg">Register Player</button>
                </form>
            </div>
        </div>

        <!-- Player List Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
                <div class="bg-zinc-50 px-6 py-4 border-b border-zinc-100 flex justify-between items-center">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-widest">Registered Players</h3>
                    <span class="text-[9px] font-black text-zinc-400 bg-white px-3 py-1 rounded-full border border-zinc-100">{{ $players->count() }} Total</span>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-50/50 text-zinc-400 text-[9px] font-black uppercase tracking-widest border-b border-zinc-100">
                            <th class="px-6 py-4">Player</th>
                            <th class="px-6 py-4">Position</th>
                            <th class="px-6 py-4">Team</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($players as $player)
                        <tr class="hover:bg-zinc-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    @if($player->image_url)
                                    <img src="{{ $player->image_url }}" class="w-10 h-10 object-cover bg-zinc-50 rounded-full border border-zinc-100">
                                    @else
                                    <div class="w-10 h-10 bg-zinc-100 rounded-full flex items-center justify-center font-black text-zinc-400 text-xs uppercase">
                                        {{ substr($player->name, 0, 1) }}
                                    </div>
                                    @endif
                                    <span class="font-extrabold text-primary text-sm uppercase tracking-tighter">{{ $player->name }}</span>
                                    @if($player->shirt_number)
                                    <span class="bg-zinc-100 text-zinc-400 text-[9px] font-black px-1.5 py-0.5 rounded border border-zinc-200">#{{ $player->shirt_number }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[9px] font-black px-2 py-1 rounded {{ $player->position === 'GK' ? 'bg-yellow-100 text-yellow-700' : (in_array($player->position, ['FWD', 'CF']) ? 'bg-red-100 text-red-700' : 'bg-primary/10 text-primary') }} uppercase tracking-widest">{{ $player->position }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-zinc-500 uppercase tracking-tighter">{{ $player->team->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                <button @click='openEdit(@json($player))' class="p-2 text-zinc-300 hover:text-primary transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <form action="{{ route('admin.players.destroy', $player->id) }}" method="POST" onsubmit="return confirm('Delete this player profile?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-zinc-300 hover:text-accent transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Player Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm">
        <div @click.away="editModal = false" class="bg-white rounded-3xl p-8 shadow-2xl border border-zinc-100 w-full max-w-md animate-scale-in">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tighter">Edit Player Profile</h3>
                <button @click="editModal = false" class="text-zinc-400 hover:text-primary transition">✕</button>
            </div>
            <form :action="'{{ url('admin/players') }}/' + editPlayer.id" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Player Name</label>
                        <input type="text" name="name" x-model="editPlayer.name" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Shirt #</label>
                        <input type="number" name="shirt_number" x-model="editPlayer.shirt_number" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" min="1" max="99">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Current Team</label>
                    <select name="team_id" x-model="editPlayer.team_id" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none appearance-none uppercase" required>
                        @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Position</label>
                    <select name="position" x-model="editPlayer.position" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none appearance-none uppercase" required>
                        <option value="GK">Goalkeeper (GK)</option>
                        <option value="DEF">Defender (DEF)</option>
                        <option value="MID">Midfielder (MID)</option>
                        <option value="FWD">Forward (FWD)</option>
                        <option value="CF">Center Forward (CF)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Update Avatar</label>
                        <div class="flex items-center gap-4 mb-2" x-show="editPlayer.image_url">
                            <img :src="editPlayer.image_url" class="w-12 h-12 rounded-full object-cover border-2 border-zinc-50 bg-zinc-50">
                        </div>
                        <input type="file" name="image" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none text-[10px]" accept="image/*">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Update Full Photo</label>
                        <div class="flex items-center gap-4 mb-2" x-show="editPlayer.full_image_url">
                            <img :src="editPlayer.full_image_url" class="w-12 h-12 rounded-xl object-cover border-2 border-zinc-50 bg-zinc-50">
                        </div>
                        <input type="file" name="full_image" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none text-[10px]" accept="image/*">
                    </div>
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
