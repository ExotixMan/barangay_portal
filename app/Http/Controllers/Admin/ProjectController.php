<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->status && in_array($request->status, ['ongoing', 'completed', 'deleted'], true)) {
            if ($request->status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->status);
            }
        }

        // Progress range filter
        if ($request->progress) {
            switch ($request->progress) {
                case '0-25':
                    $query->whereBetween('progress', [0, 25]);
                    break;
                case '26-50':
                    $query->whereBetween('progress', [26, 50]);
                    break;
                case '51-75':
                    $query->whereBetween('progress', [51, 75]);
                    break;
                case '76-100':
                    $query->whereBetween('progress', [76, 100]);
                    break;
            }
        }

        // Date range filters (optional)
        if ($request->from_date) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }
        
        if ($request->to_date) {
            $query->whereDate('expected_completion', '<=', $request->to_date);
        }

        // Stats
        $total_count = Project::withTrashed()->count();
        $ongoing_count = Project::where('status', 'ongoing')->count();
        $completed_count = Project::where('status', 'completed')->count();
        $avg_progress = round(Project::avg('progress') ?? 0);

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['title', 'location', 'start_date', 'expected_completion', 'status', 'progress', 'created_at'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $query->orderBy($sort, $direction);

        $projects = $query->paginate(10)->withQueryString();

        return view('admin.admin_project', compact(
            'projects', 
            'total_count', 
            'ongoing_count', 
            'completed_count', 
            'avg_progress'
        ));
    }

    public function store(Request $request)
    {
        // Store form type in session
        session()->flash('form_type', 'add');

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'nullable|date_format:Y-m-d',
            'expected_completion' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:ongoing,completed',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $this->validateCompletedStatusAgainstProgress($data['status'], (int) $data['progress']);

        try {
            Project::create($data);

            return redirect()->route('admin.projects.index') // FIXED: added 'admin.' prefix
                ->with('success', 'Project created successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create project. Please try again. ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // Store form type in session
        session()->flash('form_type', 'edit_' . $id);

        $project = Project::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'nullable|date_format:Y-m-d',
            'expected_completion' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:ongoing,completed',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $this->validateCompletedStatusAgainstProgress($data['status'], (int) $data['progress']);

        try {
            $project->update($data);

            return redirect()->route('admin.projects.index') // FIXED: added 'admin.' prefix
                ->with('success', 'Project updated successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update project. Please try again. ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $title = $project->title;
        $project->delete();

        return back()->with('success', 'Project "' . $title . '" archived successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:projects,id'
        ]);

        $count = count($request->ids);
        Project::whereIn('id', $request->ids)->delete();

        return back()->with('success', $count . ' selected project(s) archived successfully.');
    }

    public function restore($id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        $title = $project->title;
        $project->restore();

        return back()->with('success', 'Project "' . $title . '" restored successfully.');
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        
        if (request()->wantsJson()) {
            return response()->json($project);
        }
        
        return view('admin.projects.show', compact('project'));
    }

    public function updateProgress(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $data = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required|in:ongoing,completed'
        ]);

        $this->validateCompletedStatusAgainstProgress($data['status'], (int) $data['progress']);

        $project->update($data);

        return back()->with('success', 'Project progress updated successfully.');
    }

    private function validateCompletedStatusAgainstProgress(string $status, int $progress): void
    {
        if ($status === 'completed' && $progress !== 100) {
            throw ValidationException::withMessages([
                'status' => 'Status can only be set to Completed when progress is exactly 100%.',
            ]);
        }
    }
}