<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $events = $query->latest()->paginate(10);

        return view('admin.admin_event', compact('events'));
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        Event::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected records deleted.');
    }
}
