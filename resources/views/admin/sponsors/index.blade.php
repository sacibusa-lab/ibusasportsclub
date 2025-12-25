@extends('admin.layout')

@section('title', 'Manage Sponsors')

@section('content')
<div x-data="{ editingSponsor: null, showModal: false }" class="grid lg:grid-cols-3 gap-12">
    <!-- Add Sponsor Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6 border-b border-zinc-50 pb-4">Add New Sponsor</h3>
            <form action="{{ route('admin.sponsors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Sponsor Name</label>
                    <input type="text" name="name" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" placeholder="e.g. Nike" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Logo File</label>
                    <input type="file" name="logo" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" accept="image/*" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Competition</label>
                    <select name="competition_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                        <option value="">All Competitions (Global)</option>
                        @foreach($competitions as $competition)
                        <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Level / Grade</label>
                    <select name="level" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                        @foreach($levels as $level)
                        <option value="{{ $level }}">{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Display Order (Higher = First)</label>
                    <input type="number" name="order" value="0" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs">
                </div>
                <button type="submit" class="w-full bg-primary text-secondary font-black py-4 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-[10px] shadow-lg">Add Sponsor</button>
            </form>
        </div>
    </div>

    <!-- Sponsor List Table -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-zinc-50 text-zinc-400 text-[10px] font-black uppercase tracking-widest border-b border-zinc-100">
                        <th class="px-6 py-4">Brand</th>
                        <th class="px-6 py-4">Competition</th>
                        <th class="px-6 py-4">Level</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @foreach($sponsors as $sponsor)
                    <tr class="hover:bg-zinc-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ $sponsor->logo_url }}" class="w-12 h-12 object-contain bg-zinc-50 p-1 rounded-lg border border-zinc-100">
                                <span class="font-extrabold text-primary text-sm uppercase tracking-tighter">{{ $sponsor->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold text-zinc-500 uppercase">{{ $sponsor->competition ? $sponsor->competition->name : 'Global' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[9px] font-black px-2 py-1 rounded bg-secondary/10 text-primary uppercase tracking-widest">{{ $sponsor->level }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="editingSponsor = {{ $sponsor->toJson() }}; showModal = true" class="p-2 text-zinc-300 hover:text-primary transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <form action="{{ route('admin.sponsors.destroy', $sponsor->id) }}" method="POST" onsubmit="return confirm('Remove this sponsor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-zinc-300 hover:text-accent transition">
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

    <!-- Edit Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-zinc-900/50 backdrop-blur-sm" @click="showModal = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-zinc-100">
                <div class="sm:flex sm:items-start">
                    <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6 border-b border-zinc-50 pb-4" id="modal-title">Edit Sponsor</h3>
                        
                        <form :action="'{{ route('admin.sponsors.index') }}/' + editingSponsor?.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Sponsor Name</label>
                                <input type="text" name="name" x-model="editingSponsor.name" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Logo File (Leave blank to keep current)</label>
                                <input type="file" name="logo" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" accept="image/*">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Competition</label>
                                <select name="competition_id" x-model="editingSponsor.competition_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                                    <option value="">All Competitions (Global)</option>
                                    @foreach($competitions as $competition)
                                    <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Level / Grade</label>
                                <select name="level" x-model="editingSponsor.level" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                                    @foreach($levels as $level)
                                    <option value="{{ $level }}">{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Display Order</label>
                                <input type="number" name="order" x-model="editingSponsor.order" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-2xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs">
                            </div>
                            <div class="flex gap-4 mt-6">
                                <button type="button" @click="showModal = false" class="flex-1 bg-zinc-100 text-zinc-400 font-black py-4 rounded-2xl hover:bg-zinc-200 transition uppercase tracking-widest text-[10px]">Cancel</button>
                                <button type="submit" class="flex-1 bg-primary text-secondary font-black py-4 rounded-2xl hover:bg-primary-light transition uppercase tracking-widest text-[10px] shadow-lg">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
