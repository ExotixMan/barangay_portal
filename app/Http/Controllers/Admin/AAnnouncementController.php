<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $announcements = $query->latest()->paginate(10);

        return view('admin.admin_announcement', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            'image' => 'nullable|image',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        Announcement::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category' => $request->category,
            'image' => $imagePath,
            'is_featured' => $request->is_featured ? 1 : 0,
            'published_at' => $request->published_at,
            'status' => $request->status,
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function edit($id)
    {
        // $announcements = Announcement::findOrFail($id);
        // return view('admin.admin_announcement', compact('announcements'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $announcement->image = $request->file('image')
                ->store('announcements', 'public');
        }

        $announcement->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category' => $request->category,
            'is_featured' => $request->is_featured ? 1 : 0,
            'published_at' => $request->published_at,
            'status' => $request->status,
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function toggleFeature($id)
    {
        $announcement = Announcement::findOrFail($id);

        $announcement->is_featured = !$announcement->is_featured;
        $announcement->save();

        return back()->with('success', 'Feature status updated successfully.');
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();

        return back()->with('success', 'Announcement deleted.');
    }

    public function bulkDelete(Request $request)
    {
        Announcement::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected records deleted.');
    }
}