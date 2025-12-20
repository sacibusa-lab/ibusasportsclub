<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryItem;
use App\Services\ImageService;
use Illuminate\Http\Request;

class AdminStoryController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $stories = Story::with('items')->orderBy('created_at', 'desc')->get();
        return view('admin.stories.index', compact('stories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'link_url' => 'nullable|url',
            'media' => 'required|array',
            'media.*' => 'file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,video/mp4,video/quicktime|max:20480',
            'type' => 'required|in:image,video',
            'duration' => 'required|in:24h,permanent',
        ]);

        $expiresAt = $validated['duration'] === '24h' ? now()->addDay() : null;

        $story = Story::create([
            'title' => $validated['title'],
            'link_url' => $validated['link_url'],
            'is_active' => $request->has('is_active'),
            'expires_at' => $expiresAt,
        ]);

        foreach ($request->file('media') as $index => $file) {
            $mediaUrl = $this->imageService->upload($file, 'stories');
            
            StoryItem::create([
                'story_id' => $story->id,
                'media_url' => $mediaUrl,
                'type' => $validated['type'],
                'order' => $index,
            ]);

            // Set the first item as the main story thumbnail if not set
            if ($index === 0) {
                $story->update(['thumbnail_url' => $mediaUrl]);
            }
        }

        return back()->with('success', 'Story Group added with ' . count($validated['media']) . ' items.');
    }

    public function edit($id)
    {
        $story = Story::with('items')->findOrFail($id);
        return view('admin.stories.edit', compact('story'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'link_url' => 'nullable|url',
            'expires_at' => 'nullable|date',
            'duration' => 'required|in:24h,permanent',
            'items' => 'nullable|array',
            'items.*.type' => 'required|in:image,video',
            'items.*.link_url' => 'nullable|url',
            'items.*.order' => 'required|integer',
            'media' => 'nullable|array',
            'media.*' => 'file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,video/mp4,video/quicktime|max:20480',
            'new_items_type' => 'required_with:media|in:image,video',
        ]);

        $story = Story::findOrFail($id);
        
        $expiresAt = $validated['duration'] === '24h' ? now()->addDay() : null;

        $story->update([
            'title' => $validated['title'],
            'link_url' => $validated['link_url'],
            'is_active' => $request->has('is_active'),
            'expires_at' => $expiresAt,
        ]);

        // Update existing items
        if ($request->has('items')) {
            foreach ($request->items as $itemId => $itemData) {
                StoryItem::where('id', $itemId)->where('story_id', $story->id)->update([
                    'type' => $itemData['type'],
                    'link_url' => $itemData['link_url'],
                    'order' => $itemData['order'],
                ]);
            }
        }

        // Handle new media uploads
        if ($request->hasFile('media')) {
            $lastOrder = $story->items()->max('order') ?? -1;
            foreach ($request->file('media') as $index => $file) {
                $mediaUrl = $this->imageService->upload($file, 'stories');
                
                StoryItem::create([
                    'story_id' => $story->id,
                    'media_url' => $mediaUrl,
                    'type' => $validated['new_items_type'],
                    'order' => $lastOrder + $index + 1,
                ]);
            }
        }

        // Refresh thumbnail from the first item
        $firstItem = $story->items()->orderBy('order')->first();
        if ($firstItem) {
            $story->update(['thumbnail_url' => $firstItem->media_url]);
        }

        return redirect()->route('admin.stories.index')->with('success', 'Story updated successfully.');
    }

    public function destroy($id)
    {
        Story::findOrFail($id)->delete();
        return back()->with('success', 'Story Group removed.');
    }

    public function destroyItem($id)
    {
        $item = StoryItem::findOrFail($id);
        $storyId = $item->story_id;
        $item->delete();

        // If no items left, delete the group? 
        // For now just keep it, user might add more.
        
        return back()->with('success', 'Slide removed.');
    }
}
