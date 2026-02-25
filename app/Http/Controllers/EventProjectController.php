<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Project;

class EventProjectController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('type', 'upcoming')
            ->orderBy('event_date', 'asc')
            ->get();

        $pastEvents = Event::where('type', 'past')
            ->orderBy('event_date', 'desc')
            ->take(5)
            ->get();

        $ongoingProjects = Project::where('status', 'ongoing')->get();

        $completedProjects = Project::where('status', 'completed')->get();

        return view('barangay_system.events', compact(
            'upcomingEvents',
            'pastEvents',
            'ongoingProjects',
            'completedProjects'
        ));
    }
}
