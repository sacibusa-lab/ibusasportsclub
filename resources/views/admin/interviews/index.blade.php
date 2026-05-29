@extends('admin.layout')

@section('title', 'Manage Interviews')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-black text-primary dark:text-white uppercase tracking-tight">Interviews</h1>
        <a href="{{ route('admin.interviews.create') }}" class="bg-primary text-secondary font-black px-6 py-3 rounded-xl hover:scale-105 transition uppercase tracking-widest text-[10px] shadow-lg">
            + Add Interview
        </a>
    </div>

    @if(session('success'))
    <div class="bg-secondary/10 border border-secondary text-primary dark:text-white px-6 py-4 rounded-xl mb-6 font-bold">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-100 dark:border-zinc-800 overflow-hidden">
        <table class="w-full">
            <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-800">
                <tr>
                    <th class="text-left p-6 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Thumbnail</th>
                    <th class="text-left p-6 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Title</th>
                    <th class="text-left p-6 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Interviewee</th>
                    <th class="text-left p-6 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Order</th>
                    <th class="text-left p-6 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Featured</th>
                    <th class="text-right p-6 text-[10px] font-black text-zinc-400 dark:text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($interviews as $interview)
                <tr class="border-b border-zinc-50 hover:bg-zinc-50 dark:bg-zinc-800/50/50 transition">
                    <td class="p-6">
                        @if($interview->thumbnail_url)
                        <img src="{{ $interview->thumbnail_url }}" class="w-12 h-16 object-cover rounded-lg border border-zinc-100 dark:border-zinc-800">
                        @else
                        <div class="w-12 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center border border-zinc-100 dark:border-zinc-800">
                            <span class="text-zinc-300 dark:text-zinc-500 text-[8px] uppercase font-black">No image</span>
                        </div>
                        @endif
                    </td>
                    <td class="p-6">
                        <p class="font-black text-primary dark:text-white text-sm">{{ $interview->title }}</p>
                    </td>
                    <td class="p-6">
                        <p class="font-bold text-zinc-700 text-sm">{{ $interview->interviewee_name }}</p>
                        @if($interview->interviewee_role)
                        <p class="text-xs text-zinc-400 dark:text-zinc-500 dark:text-zinc-400">{{ $interview->interviewee_role }}</p>
                        @endif
                    </td>
                    <td class="p-6">
                        <span class="text-sm font-bold text-zinc-600 dark:text-zinc-300 dark:text-zinc-500">{{ $interview->display_order }}</span>
                    </td>
                    <td class="p-6">
                        @if($interview->is_featured)
                        <span class="bg-secondary text-primary dark:text-white text-[9px] font-black px-2 py-1 rounded uppercase">Featured</span>
                        @endif
                    </td>
                    <td class="p-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.interviews.edit', $interview->id) }}" class="bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 text-primary dark:text-white font-black px-4 py-2 rounded-lg transition text-xs uppercase">
                                Edit
                            </a>
                            <form action="{{ route('admin.interviews.destroy', $interview->id) }}" method="POST" onsubmit="return confirm('Delete this interview?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-black px-4 py-2 rounded-lg transition text-xs uppercase">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center">
                        <span class="text-zinc-300 dark:text-zinc-500 font-black uppercase tracking-widest text-sm italic">No interviews yet</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
