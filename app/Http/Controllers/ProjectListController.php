<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectListController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('items')
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('pages.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'status' => 'required|in:active,on_hold,completed,archived'
        ]);

        $validated['created_by'] = Auth::id();

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully!');
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return view('pages.projects.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,on_hold,completed,archived'
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:active,on_hold,completed,archived'
        ]);

        $project->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project status updated successfully',
            'status' => $project->status
        ]);
    }
}
