<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::where('status', 'active')->get();

        $query = Note::with(['project', 'creator'])
            ->accessibleBy(Auth::id());

        // Apply project filter
        if ($request->has('project_id') && $request->project_id != 'all') {
            $query->where('project_id', $request->project_id);
        }

        // Apply my notes filter
        if ($request->has('my_notes') && $request->my_notes) {
            $query->where('created_by', Auth::id());
        }

        $notes = $query->latest()->get();

        return view('pages.notes.index', compact('notes', 'projects'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        return view('pages.notes.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_private' => 'nullable|boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_private'] = $request->has('is_private') ? true : false;

        Note::create($validated);

        return redirect()->route('notes.index')
            ->with('success', 'Note created successfully!');
    }

    public function show($id)
    {
        $note = Note::with(['project', 'creator'])->findOrFail($id);

        if (!$note->canView(Auth::id())) {
            abort(403, 'You do not have permission to view this note.');
        }

        return view('pages.notes.show', compact('note'));
    }

    public function edit($id)
    {
        $note = Note::findOrFail($id);

        if ($note->created_by != Auth::id()) {
            abort(403, 'You do not have permission to edit this note.');
        }

        $projects = Project::where('status', 'active')->get();
        return view('pages.notes.edit', compact('note', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        if ($note->created_by != Auth::id()) {
            abort(403, 'You do not have permission to edit this note.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_private' => 'nullable|boolean',
        ]);

        $validated['is_private'] = $request->has('is_private') ? true : false;

        $note->update($validated);

        return redirect()->route('notes.show', $note->id)
            ->with('success', 'Note updated successfully!');
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);

        if ($note->created_by != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json(['success' => true, 'message' => 'Note deleted successfully']);
    }

    public function togglePin($id)
    {
        $note = Note::findOrFail($id);

        if ($note->created_by != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Toggle pin status (no limit on number of pins for notes)
        $note->is_pinned = !$note->is_pinned;
        $note->save();

        return response()->json([
            'success' => true,
            'is_pinned' => $note->is_pinned,
            'message' => $note->is_pinned ? 'Note pinned to dashboard' : 'Note unpinned'
        ]);
    }
}
