<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        App::setLocale(Session::get('locale', config('app.locale')));

        // Featured
        $featured = Announcement::where('is_featured', 1)
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        // Main query
        $query = Announcement::where('status', 'published');

        // Search
        if ($request->search) {
            $query->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('content', 'like', '%'.$request->search.'%');
        }

        // Sort
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;

            case 'important':
                $query->orderBy('is_featured', 'desc')
                    ->orderBy('published_at', 'desc');
                break;

            case 'title':
                $query->orderBy('title', 'asc');
                break;

            default:
                $query->orderBy('published_at', 'desc');
        }

        $announcements = $query
            ->paginate(6)
            ->withQueryString();

        return view(
            'barangay_system.announcement',
            compact('featured','announcements')
        )->with('fragment', 'announcements');
    }

    public function show($slug)
    {
        $announcement = Announcement::where('slug',$slug)->firstOrFail();

        // Increase views
        $announcement->increment('views');

        return view('announcement-show', compact('announcement'));
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
