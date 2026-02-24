<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index()
    {
        App::setLocale(Session::get('locale', config('app.locale')));

        $announcements = Announcement::where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        return view('barangay_system.index', compact('announcements'));
    }
}
