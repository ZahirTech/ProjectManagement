<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Note;
use App\Models\NoteAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NotesController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::where('status', 'active')->get();
        $userId = Auth::id();

        $query = Note::with(['project', 'creator', 'attachments', 'pinnedBy' => function ($q) use ($userId) {
            $q->where('user_id', $userId);
        }])
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
            'attachments.*' => 'nullable|file|max:300240' // 10MB max
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_private'] = $request->has('is_private') ? true : false;

        $note = Note::create($validated);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('note_attachments', $filename, 'public');

                NoteAttachment::create([
                    'note_id' => $note->id,
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => Auth::id()
                ]);
            }
        }

        return redirect()->route('notes.index')
            ->with('success', 'Note created successfully!');
    }

    public function show($id)
    {
        $note = Note::with(['project', 'creator', 'attachments.uploader'])->findOrFail($id);

        if (!$note->canView(Auth::id())) {
            abort(403, 'You do not have permission to view this note.');
        }

        return view('pages.notes.show', compact('note'));
    }

    public function edit($id)
    {
        $note = Note::with(['attachments'])->findOrFail($id);

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

        // Delete associated attachments from storage
        foreach ($note->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        $note->delete();

        return response()->json(['success' => true, 'message' => 'Note deleted successfully']);
    }

    public function togglePin($id)
    {
        $note = Note::findOrFail($id);

        if (!$note->canView(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userId = Auth::id();
        $existing = \App\Models\PinnedItem::where('user_id', $userId)
            ->where('pinnable_id', $note->id)
            ->where('pinnable_type', Note::class)
            ->first();

        if ($existing) {
            $existing->delete();
            $isPinned = false;
        } else {
            \App\Models\PinnedItem::create([
                'user_id' => $userId,
                'pinnable_id' => $note->id,
                'pinnable_type' => Note::class,
            ]);
            $isPinned = true;
        }

        return response()->json([
            'success' => true,
            'is_pinned' => $isPinned,
            'message' => $isPinned ? 'Note pinned to dashboard' : 'Note unpinned'
        ]);
    }

    public function uploadAttachments(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        if (!$note->canView(Auth::id())) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:300240' // 10MB max per file
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('note_attachments', $filename, 'public');

            $attachment = NoteAttachment::create([
                'note_id' => $note->id,
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => Auth::id()
            ]);

            $uploadedFiles[] = $attachment;
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedFiles) . ' file(s) uploaded successfully',
            'attachments' => $uploadedFiles
        ]);
    }

    public function deleteAttachment($id)
    {
        $attachment = NoteAttachment::findOrFail($id);
        $note = $attachment->note;

        if (!$note->canView(Auth::id())) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return response()->json(['success' => true, 'message' => 'Attachment deleted successfully']);
    }
}
