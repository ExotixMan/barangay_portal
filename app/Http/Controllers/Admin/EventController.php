<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->type && in_array($request->type, ['upcoming', 'past', 'deleted'], true)) {
            if ($request->type === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->where('type', $request->type);
            }
        }

        if ($request->month) {
            $query->whereMonth('event_date', $request->month);
        }

        if ($request->year) {
            $query->whereYear('event_date', $request->year);
        }

        $total_count = Event::withTrashed()->count();
        $upcoming_count = Event::where('type', 'upcoming')->count();
        $past_count = Event::where('type', 'past')->count();
        $total_attendees = Event::sum('attendees');

        $sort = $request->get('sort', 'event_date');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['title', 'event_date', 'location', 'attendees', 'type', 'created_at'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'event_date';
        }

        $query->orderBy($sort, $direction);

        $events = $query->paginate(10)->withQueryString();

        return view('admin.admin_event', compact(
            'events', 
            'total_count', 
            'upcoming_count', 
            'past_count', 
            'total_attendees'
        ));
    }

    public function store(Request $request)
    {
        
        session()->flash('form_type', 'add');

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'required|string|max:255',
            'attendees' => 'nullable|integer|min:0',
            'type' => 'required|in:upcoming,past',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/events'), $imageName);
                $data['image'] = 'uploads/events/' . $imageName;
            }

            if (!isset($data['attendees'])) {
                $data['attendees'] = 0;
            }

            Event::create($data);

            return redirect()->route('admin.events.index')
                ->with('success', 'Event created successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create event. Please try again. ' . $e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        
        session()->flash('form_type', 'edit_' . $id);

        $event = Event::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'required|string|max:255',
            'attendees' => 'nullable|integer|min:0',
            'type' => 'required|in:upcoming,past',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            
            if ($request->hasFile('image')) {
                
                if ($event->image && file_exists(public_path($event->image))) {
                    unlink(public_path($event->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/events'), $imageName);
                $data['image'] = 'uploads/events/' . $imageName;
            }

            $event->update($data);

            return redirect()->route('admin.events.index') 
                ->with('success', 'Event updated successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update event. Please try again. ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $event = Event::findOrFail($id);

        $title = $event->title;
        $event->delete();

        return back()->with('success', 'Event "' . $title . '" archived successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:events,id'
        ]);

        Event::whereIn('id', $request->ids)->delete();

        return back()->with('success', count($request->ids) . ' selected event(s) archived successfully.');
    }

    public function restore(int $id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        $title = $event->title;
        $event->restore();

        return back()->with('success', 'Event "' . $title . '" restored successfully.');
    }

    public function show(int $id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }

    public function duplicate(int $id)
    {
        $event = Event::findOrFail($id);
        
        $newEvent = $event->replicate();
        $newEvent->title = $event->title . ' (Copy)';
        $newEvent->save();

        return back()->with('success', 'Event duplicated successfully.');
    }
}