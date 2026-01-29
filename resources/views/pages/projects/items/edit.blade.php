@extends('layouts')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Project Item</h1>
            <p class="page-description">Update project item details</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="window.history.back()">
                ← Back
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Item Details</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('projectmng.update', $item->id) }}" method="POST" class="form-grid">
                @csrf
                @method('PUT')

                <!-- Project Selection -->
                {{-- <div class="form-group full-width">
                    <label for="projectSelect" class="form-label required">Project</label>
                    <select id="projectSelect" name="project_id" class="form-select" required>
                        <option value="">Select Project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ $item->project_id == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}

                <!-- Title -->
                <div class="form-group full-width">
                    <label for="titleInput" class="form-label required">Title</label>
                    <input type="text" id="titleInput" name="title" class="form-input"
                        value="{{ old('title', $item->title) }}" placeholder="Enter item title" required>
                </div>

                <!-- Description -->
                <div class="form-group full-width">
                    <label for="descriptionInput" class="form-label">Description</label>
                    <textarea id="descriptionInput" name="description" class="form-textarea" rows="4"
                        placeholder="Enter detailed description">{{ old('description', $item->description) }}</textarea>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="statusSelect" class="form-label required">Status</label>
                    <select id="statusSelect" name="status" class="form-select" required>
                        <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $item->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="on-hold" {{ $item->status == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                    </select>
                </div>

                <!-- Priority -->
                <div class="form-group">
                    <label for="prioritySelect" class="form-label required">Priority</label>
                    <select id="prioritySelect" name="priority" class="form-select" required>
                        <option value="low" {{ $item->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $item->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $item->priority == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $item->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <!-- Due Date -->
                <div class="form-group">
                    <label for="dueDateInput" class="form-label">Due Date</label>
                    <input type="date" id="dueDateInput" name="due_date" class="form-input"
                        value="{{ old('due_date', $item->due_date) }}">
                </div>

                <!-- Progress -->
                <div class="form-group">
                    <label for="progressInput" class="form-label">Progress (%)</label>
                    <input type="number" id="progressInput" name="progress" class="form-input"
                        value="{{ old('progress', $item->progress ?? 0) }}" min="0" max="100"
                        placeholder="0-100">
                </div>

                <!-- Private Checkbox -->
                <div class="form-group full-width">
                    <label class="checkbox-label">
                        <input type="checkbox" id="isPrivateCheckbox" name="is_private" value="1"
                            {{ old('is_private', $item->is_private) ? 'checked' : '' }}>
                        <span>Private Item (Only you can see this)</span>
                    </label>
                </div>

                <!-- Assigned To -->
                <div class="form-group full-width" id="assignmentGroup">
                    <label for="assignedToSelect" class="form-label">Assign To</label>
                    <select id="assignedToSelect" name="assigned_to" class="form-select">
                        <option value="">Not Assigned</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $item->assigned_to == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    <small class="form-help">Leave empty if item is private</small>
                </div>

                <!-- Buttons -->
                <div class="form-group full-width" style="margin-top: 20px;">
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update Item
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attachments Section -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h2>Attachments</h2>
        </div>
        <div class="card-body">
            @if ($item->attachments && $item->attachments->count() > 0)
                <div class="attachments-grid">
                    @foreach ($item->attachments as $attachment)
                        <div class="attachment-card">
                            <div class="attachment-icon">📄</div>
                            <div class="attachment-info">
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                    class="attachment-name">
                                    {{ $attachment->original_filename }}
                                </a>
                                <span class="attachment-size">
                                    {{ number_format($attachment->file_size / 1024 / 1024, 2) }} MB
                                </span>
                            </div>
                            @if ($item->created_by == auth()->id())
                                <button type="button" class="btn-icon-delete"
                                    onclick="deleteAttachment({{ $attachment->id }})" title="Delete Attachment">
                                    🗑️
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #718096; text-align: center; padding: 20px;">No attachments</p>
            @endif

            <!-- Upload New Attachment -->
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <label for="detailsFileInput" class="btn btn-secondary" style="cursor: pointer;">
                    📎 Upload New Attachment
                </label>
                <input type="file" id="detailsFileInput" style="display: none;">
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('{{ $errors->first() }}', 'error');
            });
        </script>
    @endif

    <style>
        .file-item {
            padding: 8px;
            margin-top: 8px;
            background: #f7fafc;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
@endsection
