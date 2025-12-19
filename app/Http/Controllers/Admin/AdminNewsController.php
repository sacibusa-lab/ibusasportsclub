<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\MatchModel;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminNewsController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    // === POSTS ===
    public function index()
    {
        $posts = Post::with('category')->orderBy('created_at', 'desc')->get();
        return view('admin.news.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $matches = MatchModel::with(['homeTeam', 'awayTeam'])->orderBy('match_date', 'desc')->get();
        return view('admin.news.create', compact('categories', 'tags', 'matches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'image' => 'nullable|image|max:3072',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $this->imageService->upload($request->file('image'), 'news');
        }

        $post = Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'match_id' => $request->match_id,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        $post->tags()->sync($request->tags);

        return redirect()->route('admin.news.index')->with('success', 'Post created successfully.');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        $matches = MatchModel::with(['homeTeam', 'awayTeam'])->orderBy('match_date', 'desc')->get();
        return view('admin.news.edit', compact('post', 'categories', 'tags', 'matches'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'image' => 'nullable|image|max:3072',
        ]);

        $post = Post::findOrFail($id);
        $imageUrl = $post->image_url;

        if ($request->hasFile('image')) {
            $imageUrl = $this->imageService->upload($request->file('image'), 'news');
        }
        $wasPublished = $post->is_published;
        $isPublishedNow = $request->has('is_published');

        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'match_id' => $request->match_id,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'is_published' => $isPublishedNow,
            'published_at' => ($isPublishedNow && !$wasPublished) ? now() : $post->published_at,
        ]);

        $post->tags()->sync($request->tags);

        return redirect()->route('admin.news.index')->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        Post::findOrFail($id)->delete();
        return back()->with('success', 'Post deleted successfully.');
    }

    // === CATEGORIES ===
    public function categories()
    {
        $categories = Category::withCount('posts')->get();
        return view('admin.news.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name']);
        Category::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Category added.');
    }

    public function destroyCategory($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Category removed.');
    }

    // === TAGS ===
    public function tags()
    {
        $tags = Tag::withCount('posts')->get();
        return view('admin.news.tags', compact('tags'));
    }

    public function storeTag(Request $request)
    {
        $request->validate(['name' => 'required|unique:tags,name']);
        Tag::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Tag added.');
    }

    public function destroyTag($id)
    {
        Tag::findOrFail($id)->delete();
        return back()->with('success', 'Tag removed.');
    }
}
