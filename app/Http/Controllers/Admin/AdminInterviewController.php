<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Services\ImageService;
use Illuminate\Http\Request;

class AdminInterviewController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $interviews = Interview::orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.interviews.index', compact('interviews'));
    }

    public function create()
    {
        return view('admin.interviews.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'interviewee_name' => 'required|string|max:255',
            'interviewee_role' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:3072',
            'display_order' => 'nullable|integer',
        ]);

        $thumbnailUrl = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailUrl = $this->imageService->upload($request->file('thumbnail'), 'interviews');
        }

        Interview::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'thumbnail_url' => $thumbnailUrl,
            'interviewee_name' => $request->interviewee_name,
            'interviewee_role' => $request->interviewee_role,
            'is_featured' => $request->has('is_featured'),
            'display_order' => $request->display_order ?? 0,
        ]);

        return redirect()->route('admin.interviews.index')->with('success', 'Interview created successfully.');
    }

    public function edit($id)
    {
        $interview = Interview::findOrFail($id);
        return view('admin.interviews.edit', compact('interview'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'interviewee_name' => 'required|string|max:255',
            'interviewee_role' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:3072',
            'display_order' => 'nullable|integer',
        ]);

        $interview = Interview::findOrFail($id);
        $thumbnailUrl = $interview->thumbnail_url;

        if ($request->hasFile('thumbnail')) {
            $thumbnailUrl = $this->imageService->upload($request->file('thumbnail'), 'interviews');
        }

        $interview->update([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'thumbnail_url' => $thumbnailUrl,
            'interviewee_name' => $request->interviewee_name,
            'interviewee_role' => $request->interviewee_role,
            'is_featured' => $request->has('is_featured'),
            'display_order' => $request->display_order ?? 0,
        ]);

        return redirect()->route('admin.interviews.index')->with('success', 'Interview updated successfully.');
    }

    public function destroy($id)
    {
        $interview = Interview::findOrFail($id);
        $interview->delete();

        return redirect()->route('admin.interviews.index')->with('success', 'Interview deleted successfully.');
    }
}
