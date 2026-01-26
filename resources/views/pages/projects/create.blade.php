@extends('layouts')

@section('content')
    <!-- CREATE PAGE -->
    <div id="create" class="page">
        <div class="page-header">
            <h1>Create New Item</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <form id="createForm" action="{{ route('projectmng.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Select Project *</label>
                        <select name="project_id" required>
                            <option value="">-- Choose Project --</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Item Title *</label>
                        <input type="text" name="title" placeholder="Enter item title" value="{{ old('title') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="on-hold" {{ old('status') == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Priority</label>
                        <select name="priority">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}">
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_private" id="isPrivateCheckbox" value="1"
                                {{ old('is_private') ? 'checked' : '' }} onchange="toggleAssignment()">
                            Make this item private
                        </label>
                    </div>

                    <div class="form-group" id="assignmentGroup">
                        <label>Assigned To</label>
                        <select name="assigned_to" id="assignedToSelect">
                            <option value="">-- Not Assigned --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Progress (%)</label>
                        <input type="number" name="progress" min="0" max="100"
                            value="{{ old('progress', 0) }}">
                    </div>

                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description" placeholder="Enter detailed description...">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Attach Files</label>
                        <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                            <input type="file" id="fileInput" name="attachments[]" multiple>
                            <div class="file-icon">📎</div>
                            <div>Click to upload or drag and drop</div>
                            <div style="font-size: 12px; color: #a0aec0; margin-top: 5px;">
                                PDF, DOC, XLS, Images (Max 10MB each)
                            </div>
                        </div>
                        <div class="uploaded-files-list" id="uploadedFilesList">
                            <!-- Uploaded files will appear here -->
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Create Item</button>
            </form>
        </div>
    </div>

    <script>
        function toggleAssignment() {
            const isPrivate = document.getElementById('isPrivateCheckbox').checked;
            const assignmentGroup = document.getElementById('assignmentGroup');
            const assignedToSelect = document.getElementById('assignedToSelect');

            if (isPrivate) {
                assignmentGroup.style.opacity = '0.5';
                assignedToSelect.disabled = true;
                assignedToSelect.value = '';
            } else {
                assignmentGroup.style.opacity = '1';
                assignedToSelect.disabled = false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleAssignment();

            // File upload preview
            document.getElementById('fileInput').addEventListener('change', function(e) {
                const filesList = document.getElementById('uploadedFilesList');
                filesList.innerHTML = '';

                Array.from(e.target.files).forEach(file => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    fileItem.textContent =
                        `📎 ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    filesList.appendChild(fileItem);
                });
            });
        });
    </script>

    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .file-item {
            padding: 8px;
            margin-top: 8px;
            background: #f7fafc;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
@endsection
