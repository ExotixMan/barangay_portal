<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query();

        // Search
        $search = trim((string) $request->get('search', ''));
        if ($search !== '') {
            $search = preg_replace('/[^a-zA-Z0-9\s\-\.@,]/', '', $search);
            $searchLike = '%' . strtolower($search) . '%';

            $query->where(function ($q) use ($searchLike) {
                $q->whereRaw('LOWER(title) LIKE ?', [$searchLike])
                  ->orWhereRaw('LOWER(content) LIKE ?', [$searchLike])
                  ->orWhereRaw('LOWER(category) LIKE ?', [$searchLike])
                  ->orWhereRaw('CAST(status AS TEXT) ILIKE ?', [$searchLike]);
            });
        }

        // Status filter
        $status = strtolower(trim((string) $request->get('status', '')));
        if ($status !== '' && in_array($status, ['published', 'draft', 'archived'], true)) {
            $query->where('status', $status);
        }

        // Category filter
        $category = strtolower(trim((string) $request->get('category', '')));
        if ($category !== '') {
            $query->whereRaw('LOWER(category) = ?', [$category]);
        }

        // Featured filter
        if ($request->has('featured') && $request->featured !== '') {
            $isFeaturedFilter = filter_var($request->featured, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (!is_null($isFeaturedFilter)) {
                $query->whereRaw($isFeaturedFilter ? 'is_featured IS TRUE' : 'is_featured IS FALSE');
            }
        }

        // Stats
        $total_count = Announcement::count();
        $published_count = Announcement::where('status', 'published')->count();
        $draft_count = Announcement::where('status', 'draft')->count();
        $featured_count = Announcement::whereRaw('is_featured IS TRUE')->count();

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = strtolower((string) $request->get('direction', 'desc'));
        $direction = $direction === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['title', 'category', 'is_featured', 'views', 'published_at', 'created_at', 'status'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if ($sort === 'status') {
            $query->orderByRaw("CASE status WHEN 'published' THEN 1 WHEN 'draft' THEN 2 WHEN 'archived' THEN 3 ELSE 4 END {$direction}");
        } elseif ($sort === 'is_featured') {
            $query->orderBy('is_featured', $direction);
        } elseif ($sort === 'published_at') {
            // Keep nulls consistently last regardless of direction.
            $query->orderByRaw('published_at IS NULL ASC')
                ->orderBy('published_at', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        // Secondary sort follows selected direction so status-only buckets
        if ($sort !== 'created_at') {
            $query->orderBy('created_at', $direction);
        }

        $query->orderBy('id', $direction);

        $announcements = $query->paginate(10)->withQueryString();

        return view('admin.admin_announcement', compact(
            'announcements', 
            'total_count', 
            'published_count', 
            'draft_count', 
            'featured_count'
        ));
    }

    public function store(Request $request)
    {
        // Store form type in session
        session()->flash('form_type', 'add');

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:important,events,health,services,other',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:published,draft,archived',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('announcement_pic'), $imageName);
                $data['image'] = 'announcement_pic/' . $imageName;
            }

            // Generate slug
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(6);

            // Set published_at if status is published
            if ($request->status == 'published' && !$request->published_at) {
                $data['published_at'] = now();
            }

            // PostgreSQL-safe boolean literal to avoid boolean/integer binding mismatch.
            unset($data['is_featured']);

            $announcement = Announcement::create($data);

            Announcement::where('id', $announcement->id)
                ->update([
                    'is_featured' => DB::raw($request->boolean('is_featured') ? 'TRUE' : 'FALSE')
                ]);

            return redirect()->route('admin.announcements.index') // FIXED: added 'admin.' prefix
                ->with('success', 'Announcement created successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create announcement. Please try again. ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Store form type in session
        session()->flash('form_type', 'edit_' . $id);

        $announcement = Announcement::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:important,events,health,services,other',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:published,draft,archived',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($announcement->image && file_exists(public_path($announcement->image))) {
                    unlink(public_path($announcement->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('announcement_pic'), $imageName);
                $data['image'] = 'announcement_pic/' . $imageName;
            }

            // Update slug only if title changed
            if ($announcement->title != $request->title) {
                $data['slug'] = Str::slug($request->title) . '-' . Str::random(6);
            }

            // Set published_at if status changed to published
            if ($request->status == 'published' && !$request->published_at && $announcement->status != 'published') {
                $data['published_at'] = now();
            }

            // PostgreSQL-safe boolean literal to avoid boolean/integer binding mismatch.
            unset($data['is_featured']);

            $announcement->update($data);

            Announcement::where('id', $announcement->id)
                ->update([
                    'is_featured' => DB::raw($request->boolean('is_featured') ? 'TRUE' : 'FALSE')
                ]);

            return redirect()->route('admin.announcements.index') // FIXED: added 'admin.' prefix
                ->with('success', 'Announcement updated successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update announcement. Please try again. ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        // Delete image
        if ($announcement->image && file_exists(public_path($announcement->image))) {
            unlink(public_path($announcement->image));
        }

        $title = $announcement->title;
        $announcement->delete();

        return back()->with('success', 'Announcement "' . $title . '" deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:announcements,id'
        ]);

        $announcements = Announcement::whereIn('id', $request->ids)->get();

        // Delete images
        foreach ($announcements as $announcement) {
            if ($announcement->image && file_exists(public_path($announcement->image))) {
                unlink(public_path($announcement->image));
            }
        }

        Announcement::whereIn('id', $request->ids)->delete();

        return back()->with('success', count($request->ids) . ' selected announcement(s) deleted successfully.');
    }

    public function toggleFeature($id)
    {
        $announcement = Announcement::findOrFail($id);

        Announcement::where('id', $announcement->id)
            ->update(['is_featured' => DB::raw('NOT is_featured')]);

        $announcement->refresh();

        $status = $announcement->is_featured ? 'featured' : 'unfeatured';

        return back()->with('success', 'Announcement ' . $status . ' successfully.');
    }

    public function trackView($slug)
    {
        $announcement = Announcement::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $announcement->increment('views');

        return response()->json([
            'success' => true,
            'views' => $announcement->views,
        ]);
    }

    public function show($slug)
    {
        $announcement = Announcement::where('slug', $slug)->firstOrFail();
        
        // Increment views
        $announcement->increment('views');

        return view('announcements.show', compact('announcement'));
    }
}