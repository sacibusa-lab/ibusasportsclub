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
            'media' => 'nullable|array',
            'media.*' => 'file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,video/mp4,video/quicktime|max:20480',
            'type' => 'required|in:image,video',
            'duration' => 'required|in:24h,permanent',
        ]);

        $story = Story::findOrFail($id);
        
        $expiresAt = $validated['duration'] === '24h' ? now()->addDay() : null;

        $story->update([
            'title' => $validated['title'],
            'link_url' => $validated['link_url'],
            'is_active' => $request->has('is_active'),
            'expires_at' => $expiresAt,
        ]);

        if ($request->hasFile('media')) {
            $lastOrder = $story->items()->max('order') ?? -1;
            foreach ($request->file('media') as $index => $file) {
                $mediaUrl = $this->imageService->upload($file, 'stories');
                
                StoryItem::create([
                    'story_id' => $story->id,
                    'media_url' => $mediaUrl,
                    'type' => $validated['type'],
                    'order' => $lastOrder + $index + 1,
                ]);

                // Update thumbnail if it was empty
                if (!$story->thumbnail_url) {
                    $story->update(['thumbnail_url' => $mediaUrl]);
                }
            }
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
