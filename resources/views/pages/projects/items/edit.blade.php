@extends('layouts')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Assignment</h1>
            <p class="page-description">Update project Assignment details</p>
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
            <h2>Attachments ({{ $item->attachments->count() }})</h2>
        </div>
        <div class="card-body">
            @if ($item->attachments && $item->attachments->count() > 0)
                <div class="attachments-grid">
                    @foreach ($item->attachments as $attachment)
                        <div class="attachment-card">
                            <div class="attachment-icon">
                                @if (str_starts_with($attachment->mime_type, 'image/'))
                                    🖼️
                                @elseif(str_contains($attachment->mime_type, 'pdf'))
                                    📄
                                @elseif(str_contains($attachment->mime_type, 'spreadsheet') ||
                                        str_contains($attachment->original_filename, '.xlsx') ||
                                        str_contains($attachment->original_filename, '.xls'))
                                    📊
                                @elseif(str_contains($attachment->mime_type, 'word') ||
                                        str_contains($attachment->original_filename, '.docx') ||
                                        str_contains($attachment->original_filename, '.doc'))
                                    📝
                                @else
                                    📎
                                @endif
                            </div>
                            <div class="attachment-info">
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                    class="attachment-name" title="{{ $attachment->original_filename }}">
                                    {{ Str::limit($attachment->original_filename, 40) }}
                                </a>
                                <span class="attachment-size">
                                    {{ number_format($attachment->file_size / 1024, 0) }} KB
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
                <p style="color: #718096; text-align: center; padding: 20px;">No attachments yet</p>
            @endif

            <!-- Upload New Attachment -->
            <div id="uploadSection" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <label for="detailsFileInput" class="btn btn-secondary" style="cursor: pointer; display: inline-block;">
                    📎 Upload New Attachment(s)
                </label>
                <input type="file" id="detailsFileInput" multiple style="display: none;">
                <small style="display: block; margin-top: 8px; color: #718096;">Max 10MB per file</small>
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

        .attachments-grid {
            display: grid;
            gap: 12px;
        }

        .attachment-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .attachment-card:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
        }

        .attachment-icon {
            font-size: 2rem;
            flex-shrink: 0;
        }

        .attachment-info {
            flex: 1;
            min-width: 0;
        }

        .attachment-name {
            display: block;
            font-weight: 500;
            color: #2d3748;
            text-decoration: none;
            margin-bottom: 4px;
            word-break: break-word;
        }

        .attachment-name:hover {
            color: #4299e1;
            text-decoration: underline;
        }

        .attachment-size {
            font-size: 0.875rem;
            color: #718096;
        }

        .btn-icon-delete {
            background: #fed7d7;
            color: #c53030;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .btn-icon-delete:hover {
            background: #fc8181;
            color: white;
        }

        /* Upload indicator styles */
        .upload-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            color: #2c5282;
            font-weight: 500;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #bee3f8;
            border-top-color: #3182ce;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
    </style>

    <script>
        // File upload handler for edit page
        document.getElementById('detailsFileInput')?.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files.length === 0) return;

            const formData = new FormData();

            // Add all selected files
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            // Show uploading indicator
            const uploadSection = document.getElementById('uploadSection');
            const originalHTML = uploadSection.innerHTML;
            uploadSection.innerHTML = `
                <div class="upload-indicator">
                    <div class="spinner"></div>
                    <span>Uploading ${files.length} file(s)...</span>
                </div>
            `;

            // Upload files
            fetch('{{ route('projectmng.uploadAttachments', $item->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        if (typeof showNotification === 'function') {
                            showNotification(data.message || 'Files uploaded successfully!', 'success');
                        }
                        // Reload page to show new attachments
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        alert(data.message || 'Upload failed');
                        uploadSection.innerHTML = originalHTML;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Upload failed. Please try again.');
                    uploadSection.innerHTML = originalHTML;
                });

            // Reset input
            e.target.value = '';
        });

        // Delete attachment function (already working, just keeping it here for reference)
        function deleteAttachment(attachmentId) {
            if (!confirm('Are you sure you want to delete this attachment?')) {
                return;
            }

            fetch(`/projectmng/attachments/${attachmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (typeof showNotification === 'function') {
                            showNotification('Attachment deleted successfully!', 'success');
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        alert(data.message || 'Delete failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Delete failed. Please try again.');
                });
        }

        // Handle private checkbox - disable assignment when private
        document.getElementById('isPrivateCheckbox')?.addEventListener('change', function() {
            const assignedToSelect = document.getElementById('assignedToSelect');
            const assignmentGroup = document.getElementById('assignmentGroup');

            if (this.checked) {
                assignedToSelect.value = '';
                assignedToSelect.disabled = true;
                assignmentGroup.style.opacity = '0.5';
            } else {
                assignedToSelect.disabled = false;
                assignmentGroup.style.opacity = '1';
            }
        });

        // Check on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isPrivateCheckbox = document.getElementById('isPrivateCheckbox');
            if (isPrivateCheckbox && isPrivateCheckbox.checked) {
                const assignedToSelect = document.getElementById('assignedToSelect');
                const assignmentGroup = document.getElementById('assignmentGroup');
                assignedToSelect.disabled = true;
                assignmentGroup.style.opacity = '0.5';
            }
        });
    </script>
@endsection
