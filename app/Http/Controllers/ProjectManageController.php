<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectManageController extends Controller
{
    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        $users = User::select('id', 'name', 'email')->get();

        return view('pages.projects.items.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,processing,completed,on-hold',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'progress' => 'nullable|integer|min:0|max:100',
            'is_private' => 'nullable|boolean',
            'attachments.*' => 'nullable|file|max:10240' // 10MB max
        ]);

        // If private, remove assignment
        if ($request->has('is_private') && $request->is_private) {
            $validated['assigned_to'] = null;
        }

        $validated['created_by'] = Auth::id();
        $validated['is_private'] = $request->has('is_private') ? true : false;

        $item = ProjectItem::create($validated);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('attachments', $filename, 'public');

                Attachment::create([
                    'project_item_id' => $item->id,
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => Auth::id()
                ]);
            }
        }

        return redirect()->route('projectmng.list')
            ->with('success', 'Project item created successfully!');
    }

    public function list(Request $request)
    {
        $projects = Project::where('status', 'active')->get();

        // Get counts for tabs with filters applied
        $baseQuery = ProjectItem::accessibleBy(Auth::id());

        // Apply project filter to counts
        if ($request->has('project_id') && $request->project_id != 'all') {
            $baseQuery = $baseQuery->where('project_id', $request->project_id);
        }

        // Apply my items filter to counts
        if ($request->has('my_items') && $request->my_items) {
            $baseQuery = $baseQuery->assignedTo(Auth::id());
        }

        // Get counts for each status
        $counts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'processing' => (clone $baseQuery)->where('status', 'processing')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'on-hold' => (clone $baseQuery)->where('status', 'on-hold')->count(),
        ];

        return view('pages.projects.items.list', compact('projects', 'counts'));
    }

    public function getTabItems(Request $request, $status)
    {
        $query = ProjectItem::with(['project', 'assignedUser', 'attachments'])
            ->accessibleBy(Auth::id());

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Apply project filter
        if ($request->has('project_id') && $request->project_id != 'all') {
            $query->where('project_id', $request->project_id);
        }

        // Apply my items filter
        if ($request->has('my_items') && $request->my_items) {
            $query->assignedTo(Auth::id());
        }

        $items = $query->latest()->get();

        return response()->json([
            'success' => true,
            'items' => $items,
            'count' => $items->count()
        ]);
    }

    public function show($id)
    {
        $item = ProjectItem::with(['project', 'creator', 'assignedUser', 'attachments.uploader'])
            ->findOrFail($id);

        // Check access
        if (!$item->canView(Auth::id())) {
            abort(403, 'You do not have permission to view this item.');
        }

        return view('pages.projects.items.show', compact('item'));
    }

    public function edit($id)
    {
        $item = ProjectItem::with(['project', 'attachments'])->findOrFail($id);

        // Check access - only creator can edit
        if ($item->created_by != Auth::id()) {
            abort(403, 'You do not have permission to edit this item.');
        }

        $projects = Project::where('status', 'active')->get();
        $users = User::select('id', 'name', 'email')->get();

        return view('pages.projects.items.edit', compact('item', 'projects', 'users'));
    }

    public function update(Request $request, $id)
    {
        $item = ProjectItem::findOrFail($id);

        // Check access
        if ($item->created_by != Auth::id()) {
            abort(403, 'You do not have permission to edit this item.');
        }

        $validated = $request->validate([
            // 'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,processing,completed,on-hold',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'progress' => 'nullable|integer|min:0|max:100',
            'is_private' => 'nullable|boolean',
        ]);

        // If private, remove assignment
        if ($request->has('is_private') && $request->is_private) {
            $validated['assigned_to'] = null;
        }

        $validated['is_private'] = $request->has('is_private') ? true : false;

        // Set completed_at if status changed to completed
        if ($validated['status'] == 'completed' && $item->status != 'completed') {
            $validated['completed_at'] = now();
        }

        $item->update($validated);

        return redirect()->route('projectmng.show', $item->id)
            ->with('success', 'Item updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $item = ProjectItem::findOrFail($id);

        if (!$item->canView(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,on-hold'
        ]);

        if ($validated['status'] == 'completed' && $item->status != 'completed') {
            $validated['completed_at'] = now();
        }

        $item->update($validated);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function destroy($id)
    {
        $item = ProjectItem::findOrFail($id);

        // Only creator can delete
        if ($item->created_by != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Delete associated attachments from storage
        foreach ($item->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
    }

    public function uploadAttachment(Request $request, $id)
    {
        $item = ProjectItem::findOrFail($id);

        if (!$item->canView(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('attachments', $filename, 'public');

        $attachment = Attachment::create([
            'project_item_id' => $item->id,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'attachment' => $attachment
        ]);
    }

    public function deleteAttachment($id)
    {
        $attachment = Attachment::findOrFail($id);
        $item = $attachment->projectItem;

        if (!$item->canView(Auth::id())) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return response()->json(['success' => true, 'message' => 'Attachment deleted successfully']);
    }

    public function togglePin($id)
    {
        $item = ProjectItem::findOrFail($id);

        // Only creator can pin/unpin
        if ($item->created_by != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Toggle pin status (allow multiple pins)
        $item->is_pinned = !$item->is_pinned;
        $item->save();

        return response()->json([
            'success' => true,
            'is_pinned' => $item->is_pinned,
            'message' => $item->is_pinned ? 'Item pinned to dashboard' : 'Item unpinned'
        ]);
    }

    public function uploadAttachments(Request $request, $id)
    {
        $item = ProjectItem::findOrFail($id);

        if (!$item->canView(Auth::id())) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:10240' // 10MB max per file
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('attachments', $filename, 'public');

            $attachment = Attachment::create([
                'project_item_id' => $item->id,
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
}
