<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        // $announcements = Announcement::latest()
        //     ->whereNotNull('published_at')
        //     ->get();
        $announcements = Announcement::latest()->get();

        return view('barangay_system.announcement', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required'
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'is_featured' => $request->is_featured ?? false,
            'published_at' => now()
        ]);

        return redirect()->back();
    }
}
