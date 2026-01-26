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

        return view('pages.projects.create', compact('projects', 'users'));
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
        $query = ProjectItem::with(['project', 'assignedUser', 'attachments'])
            ->accessibleBy(Auth::id());

        // Filter by project
        if ($request->has('project_id') && $request->project_id != 'all') {
            $query->where('project_id', $request->project_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Show only assigned to me
        if ($request->has('my_items') && $request->my_items) {
            $query->assignedTo(Auth::id());
        }

        $items = $query->latest()->paginate(15);
        $projects = Project::where('status', 'active')->get();

        // Get counts for tabs
        $counts = [
            'pending' => ProjectItem::accessibleBy(Auth::id())->where('status', 'pending')->count(),
            'processing' => ProjectItem::accessibleBy(Auth::id())->where('status', 'processing')->count(),
            'completed' => ProjectItem::accessibleBy(Auth::id())->where('status', 'completed')->count(),
            'on-hold' => ProjectItem::accessibleBy(Auth::id())->where('status', 'on-hold')->count(),
        ];

        return view('pages.projects.list', compact('items', 'projects', 'counts'));
    }

    public function show($id)
    {
        $item = ProjectItem::with(['project', 'creator', 'assignedUser', 'attachments.uploader'])
            ->findOrFail($id);

        // Check access
        if (!$item->canView(Auth::id())) {
            abort(403, 'You do not have permission to view this item.');
        }

        return view('pages.projects.show', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = ProjectItem::findOrFail($id);

        // Check access
        if (!$item->canView(Auth::id())) {
            abort(403, 'You do not have permission to edit this item.');
        }

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

        return redirect()->back()->with('success', 'Item updated successfully!');
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
            abort(403, 'You do not have permission to delete this item.');
        }

        $item->delete();

        return redirect()->route('projectmng.list')
            ->with('success', 'Item deleted successfully!');
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
            abort(403, 'Unauthorized');
        }

        $attachment->delete();

        return redirect()->back()->with('success', 'Attachment deleted successfully!');
    }
}
