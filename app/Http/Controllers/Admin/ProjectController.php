<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $projects = $query->latest()->paginate(10);

        return view('admin.admin_project', compact('projects'));
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'Project deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        Project::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected records deleted.');
    }
}
