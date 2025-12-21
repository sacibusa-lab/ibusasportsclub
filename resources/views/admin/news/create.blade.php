@extends('admin.layout')

@section('title', 'Add New Post')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-zinc-100">
        <form id="articleForm" action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Article Title</label>
                <input type="text" name="title" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-black text-primary text-xl focus:ring-2 focus:ring-primary outline-none transition uppercase" placeholder="Enter headline..." required>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Category</label>
                    <select name="category_id" class="w-full bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Article Cover Image</label>
                    <input type="file" name="image" class="w-full bg-zinc-50 border border-zinc-100 p-3 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition text-xs" accept="image/*">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Related Match (Match Report)</label>
                <select name="match_id" class="w-full bg-zinc-50 border border-zinc-100 p-4 rounded-xl font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition uppercase text-xs">
                    <option value="">None / Not a Match Report</option>
                    @foreach($matches as $m)
                        <option value="{{ $m->id }}">
                            {{ $m->match_date->format('d M') }} | {{ $m->homeTeam->name }} v {{ $m->awayTeam->name }} ({{ $m->stage }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Content</label>
                <div class="bg-zinc-50 rounded-2xl border border-zinc-100 overflow-hidden">
                    <input type="hidden" name="content" id="contentInput">
                    <div id="editor" class="min-h-[400px] bg-zinc-50 font-medium text-zinc-700 text-sm leading-relaxed"></div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-zinc-400 border-b border-zinc-50 pb-2 uppercase tracking-widest">Tags</label>
                <div class="flex flex-wrap gap-3 p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                    @foreach($tags as $tag)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="hidden peer">
                        <span class="px-3 py-1.5 rounded-lg border border-zinc-200 text-[10px] font-black uppercase tracking-widest text-zinc-400 transition peer-checked:bg-primary peer-checked:text-secondary peer-checked:border-primary group-hover:bg-zinc-100">
                            {{ $tag->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-zinc-50">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_published" value="1" class="hidden peer" checked>
                    <div class="w-12 h-6 bg-zinc-200 rounded-full relative transition peer-checked:bg-secondary">
                        <div class="absolute inset-y-1 left-1 w-4 h-4 bg-white rounded-full transition translate-x-0 peer-checked:translate-x-6"></div>
                    </div>
                    <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest group-hover:text-primary transition">Publish Immediately</span>
                </label>

                <div class="flex gap-4">
                    <a href="{{ route('admin.news.index') }}" class="px-8 py-3 border border-zinc-100 rounded-xl font-black text-[10px] text-zinc-400 uppercase tracking-widest hover:bg-zinc-50 transition">Cancel</a>
                    <button type="submit" class="bg-primary text-secondary font-black px-12 py-3 rounded-xl hover:scale-105 transition uppercase tracking-widest text-[10px] shadow-lg">Save Article</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: 1px solid #f4f4f5;
        background: #fafafa;
        padding: 12px;
    }
    .ql-container.ql-snow {
        border: none;
        font-family: inherit;
    }
    .ql-editor {
        min-height: 400px;
        font-size: 14px;
        color: #3f3f46;
    }
    .ql-editor.ql-blank::before {
        color: #a1a1aa;
        font-style: normal;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Write article content here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Custom Image Handler
    quill.getModule('toolbar').addHandler('image', function() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            if (/^image\//.test(file.type)) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("admin.news.upload-image") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.url) {
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', result.url);
                    }
                })
                .catch(error => {
                    console.error('Error uploading image:', error);
                    alert('Image upload failed.');
                });
            }
        };
    });

    document.getElementById('articleForm').onsubmit = function() {
        var content = document.querySelector('input[name=content]');
        content.value = quill.root.innerHTML;
        
        if (quill.getText().trim().length === 0) {
            alert('Please enter some content for the article.');
            return false;
        }
    };
</script>
@endpush
@endsection
