@extends('admin.layout')

@section('title', 'Referee Management')

@section('content')
<div x-data="{ 
    editModal: false, 
    editReferee: { id: '', name: '', nationality: '', image_url: '', has_fifa_badge: false },
    openEdit(referee) {
        this.editReferee = { 
            id: referee.id, 
            name: referee.name, 
            nationality: referee.nationality,
            image_url: referee.image_url,
            has_fifa_badge: referee.has_fifa_badge
        };
        this.editModal = true;
    }
}">
    <div class="grid lg:grid-cols-3 gap-12">
        <!-- Add Referee Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100 sticky top-28">
                <h3 class="text-xl font-black mb-6 text-primary uppercase italic tracking-tighter">Add New Referee</h3>
                <form action="{{ route('admin.referees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Referee Name</label>
                        <input type="text" name="name" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" placeholder="e.g. PIERLUIGI COLLINA" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Nationality</label>
                        <input type="text" name="nationality" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" placeholder="e.g. ITALY">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Photo</label>
                        <input type="file" name="image" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none text-xs" accept="image/*">
                    </div>
                    <div class="flex items-center gap-3 bg-zinc-50 p-4 rounded-2xl border border-zinc-200">
                        <input type="checkbox" name="has_fifa_badge" value="1" id="fifa_badge" class="w-5 h-5 rounded border-zinc-300 text-primary focus:ring-primary">
                        <label for="fifa_badge" class="text-xs font-bold text-zinc-500 uppercase cursor-pointer">Has FIFA Badge</label>
                    </div>
                    <button type="submit" class="w-full bg-primary text-white font-black p-4 rounded-2xl hover:scale-[1.02] transition shadow-lg mt-4 uppercase tracking-widest text-[10px]">Add Referee</button>
                </form>
            </div>
        </div>

        <!-- Referees List -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-xl font-black text-zinc-400 uppercase italic tracking-tighter">Current Referees</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($referees as $referee)
                <div class="bg-white rounded-3xl p-6 border border-zinc-100 shadow-sm flex items-center justify-between group hover:border-zinc-200 transition">
                    <div class="flex items-center gap-4">
                        @if($referee->image_url)
                        <img src="{{ $referee->image_url }}" class="w-16 h-16 rounded-2xl object-cover bg-zinc-50">
                        @else
                        <div class="w-16 h-16 rounded-2xl bg-zinc-100 flex items-center justify-center text-zinc-300 font-black text-xl uppercase">
                            {{ substr($referee->name, 0, 1) }}
                        </div>
                        @endif
                        <div>
                            <h4 class="font-black text-primary text-sm uppercase tracking-tight flex items-center gap-2">
                                {{ $referee->name }}
                                @if($referee->has_fifa_badge)
                                <span class="bg-primary text-white text-[8px] px-1.5 py-0.5 rounded font-black tracking-widest border border-white shadow-sm">FIFA</span>
                                @endif
                            </h4>
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">{{ $referee->nationality ?? 'Unknown' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                        <button @click="openEdit({ id: {{ $referee->id }}, name: '{{ addslashes($referee->name) }}', nationality: '{{ addslashes($referee->nationality) }}', image_url: '{{ $referee->image_url }}', has_fifa_badge: {{ $referee->has_fifa_badge ? 'true' : 'false' }} })" class="w-8 h-8 flex items-center justify-center bg-zinc-50 rounded-lg text-zinc-400 hover:text-primary hover:bg-zinc-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <form action="{{ route('admin.referees.destroy', $referee->id) }}" method="POST" onsubmit="return confirm('Delete this referee?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center bg-zinc-50 rounded-lg text-zinc-400 hover:text-rose-500 hover:bg-rose-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-2 bg-zinc-50 rounded-3xl p-12 text-center text-zinc-400 font-bold uppercase tracking-widest text-xs italic">
                    No referees found.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm">
        <div @click.away="editModal = false" class="bg-white rounded-3xl p-8 shadow-2xl border border-zinc-100 w-full max-w-md animate-scale-in">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-primary uppercase italic tracking-tighter">Edit Referee</h3>
                <button @click="editModal = false" class="text-zinc-400 hover:text-primary transition">✕</button>
            </div>
            <form :action="'{{ url('admin/referees') }}/' + editReferee.id" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Referee Name</label>
                    <input type="text" name="name" x-model="editReferee.name" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Nationality</label>
                    <input type="text" name="nationality" x-model="editReferee.nationality" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none uppercase">
                </div>
                    <div class="flex items-center gap-3 bg-zinc-50 p-4 rounded-2xl border border-zinc-200 mb-4">
                        <input type="checkbox" name="has_fifa_badge" value="1" x-model="editReferee.has_fifa_badge" id="edit_fifa_badge" class="w-5 h-5 rounded border-zinc-300 text-primary focus:ring-primary">
                        <label for="edit_fifa_badge" class="text-xs font-bold text-zinc-500 uppercase cursor-pointer">Has FIFA Badge</label>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2">Update Photo</label>
                        <div class="flex items-center gap-4 mb-2" x-show="editReferee.image_url">
                            <img :src="editReferee.image_url" class="w-12 h-12 object-cover bg-zinc-50 rounded-lg">
                            <span class="text-[10px] font-bold text-zinc-400 italic">Current Photo</span>
                        </div>
                        <input type="file" name="image" class="w-full p-4 rounded-2xl border border-zinc-200 bg-zinc-50 font-bold focus:ring-2 focus:ring-secondary outline-none text-xs" accept="image/*">
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
